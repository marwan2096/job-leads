<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class ResumeExtractService
{
    public function extractResume(string $fileUrl)
    {
        try {
            Log::info('Starting resume extraction', ['fileUrl' => $fileUrl]);

            // Extract text from PDF
            $rawText = $this->extractTextFromPdf($fileUrl);

            Log::info('PDF text extraction completed', [
                'text_length' => strlen($rawText),
                'text_preview' => substr($rawText, 0, 200)
            ]);

            if (empty(trim($rawText))) {
                Log::error('No text could be extracted from the PDF - empty content');
                return $this->getDefaultResult();
            }

            // Check if text is too short (might be a corrupted PDF)
            if (strlen($rawText) < 50) {
                Log::warning('Extracted text is very short', ['text' => $rawText]);
            }

            // Optimize text to save tokens and reduce cost
            $optimizedText = $this->optimizeTextForTokens($rawText, 1500);
            Log::info('Sending to GPT-3.5-Turbo', [
                'original_length' => strlen($rawText),
                'optimized_length' => strlen($optimizedText)
            ]);

            // Call GPT-3.5-Turbo API
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Extract summary, skills, experience, education from the resume. Return ONLY valid JSON. No markdown, no explanations, no additional text. Format: {"summary":"", "skills":"", "experience":"", "education":""}'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Resume text: {$optimizedText}"
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.1,
            ]);

            // Log token usage for monitoring
            if (isset($response->usage)) {
                Log::info('GPT-3.5-Turbo token usage', [
                    'prompt_tokens' => $response->usage->promptTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                    'total_tokens' => $response->usage->totalTokens,
                    'estimated_cost_usd' => ($response->usage->totalTokens * 0.000002) // ~$0.002 per 1K tokens
                ]);
            }

            $result = $response->choices[0]->message->content;
            Log::info('GPT-3.5-Turbo response received', [
                'response_length' => strlen($result),
                'response_preview' => substr($result, 0, 500)
            ]);

            // Clean the response - remove markdown code blocks
            $cleanedResult = preg_replace('/```json\s*|\s*```/', '', $result);
            $cleanedResult = trim($cleanedResult);

            // Try to extract JSON if it's embedded in text
            if (!str_starts_with($cleanedResult, '{')) {
                preg_match('/\{.*\}/s', $cleanedResult, $matches);
                if (isset($matches[0])) {
                    $cleanedResult = $matches[0];
                    Log::info('Extracted JSON from text', ['json' => $cleanedResult]);
                }
            }

            // Parse JSON response
            $parsedResult = json_decode($cleanedResult, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parsing failed', [
                    'error' => json_last_error_msg(),
                    'cleaned_result' => $cleanedResult,
                    'original_response' => $result
                ]);

                // Try fallback parsing
                $parsedResult = $this->parseNonJsonResponse($result);
                Log::info('Fallback parsing result', ['parsed' => $parsedResult]);
            }

            // Ensure all expected keys exist
            $expectedKeys = ['summary', 'skills', 'experience', 'education'];
            foreach ($expectedKeys as $key) {
                if (!isset($parsedResult[$key])) {
                    $parsedResult[$key] = '';
                }
            }

            // Convert any arrays to strings for database storage
            $finalResult = [
                'summary' => $this->convertToString($parsedResult['summary']),
                'skills' => $this->convertToString($parsedResult['skills']),
                'experience' => $this->convertToString($parsedResult['experience']),
                'education' => $this->convertToString($parsedResult['education']),
            ];

            Log::info('Final extracted result', ['result' => $finalResult]);

            return $finalResult;

        } catch (\Exception $e) {
            Log::error('Error extracting resume information', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file_url' => $fileUrl
            ]);

            return $this->getDefaultResult();
        }
    }

    /**
     * Optimize text to reduce token usage and cost
     */
    private function optimizeTextForTokens(string $text, int $maxLength = 1500): string
    {
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Remove common noisy patterns to save tokens
        $patterns = [
            '/[^\x20-\x7E]/', // Remove non-ASCII characters
            '/\b(?:www\.|https?:\/\/)\S+/', // Remove URLs
            '/\b\d{3}[-.]?\d{3}[-.]?\d{4}\b/', // Remove phone numbers (US format)
            '/\b\d{3}[-.]?\d{4}\b/', // Remove short phone numbers
            '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', // Remove emails
            '/\b(?:linkedin\.com|facebook\.com|twitter\.com)\/\S+/i', // Remove social media links
        ];

        foreach ($patterns as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        // Remove multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Truncate if still too long
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength);
        }

        return $text;
    }










    /**
     * Extract text from PDF file
     */
    private function extractTextFromPdf(string $fileUrl): string
    {
        Log::info('Starting PDF text extraction', ['fileUrl' => $fileUrl]);

        $tempFile = tempnam(sys_get_temp_dir(), 'resume');

        try {
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            if (!$filePath) {
                throw new \Exception('Invalid file URL');
            }

            $filename = basename($filePath);
            $storagePath = "resumes/{$filename}";

            Log::info('Looking for file', ['storagePath' => $storagePath]);

            if (!Storage::disk('cloud')->exists($storagePath)) {
                throw new \Exception('File not found in cloud storage: ' . $storagePath);
            }

            $pdfContent = Storage::disk('cloud')->get($storagePath);
            if (!$pdfContent) {
                throw new \Exception('Failed to read file content');
            }

            Log::info('PDF content retrieved', ['size' => strlen($pdfContent)]);

            file_put_contents($tempFile, $pdfContent);

            // Check if pdf-to-text is installed
            $pdfToTextPaths = ['/opt/homebrew/bin/pdftotext', '/usr/bin/pdftotext', '/usr/local/bin/pdftotext'];
            $pdfToTextAvailable = false;
            $usedPath = null;

            foreach ($pdfToTextPaths as $path) {
                if (file_exists($path)) {
                    $pdfToTextAvailable = true;
                    $usedPath = $path;
                    break;
                }
            }

            if (!$pdfToTextAvailable) {
                Log::error('pdftotext not found in any of the expected paths', ['paths' => $pdfToTextPaths]);
                throw new \Exception('pdf-to-text is not installed. Please install poppler-utils.');
            }

            Log::info('Using pdftotext at', ['path' => $usedPath]);

            // Extract text from the pdf file
            $instance = new Pdf($usedPath);
            $instance->setPdf($tempFile);
            $text = $instance->text();

            Log::info('Text extraction successful', ['text_length' => strlen($text)]);

            // Clean up the temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            return $text;

        } catch (\Exception $e) {
            Log::error('Error in extractTextFromPdf', [
                'error' => $e->getMessage(),
                'tempFile' => $tempFile
            ]);

            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            throw $e;
        }
    }


 public function analyzeResume($jobVacancy, $resumeData) {
        try {
            $jobDetails = json_encode([
                'job_title' => $jobVacancy->title,
                'job_description' => $jobVacancy->description,
                'job_location' => $jobVacancy->location,
                'job_type' => $jobVacancy->type,
                'job_salary' => $jobVacancy->salary,
            ]);

            $resumeDetails = json_encode($resumeData);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert HR professional and job recruiter. You are given a job vacancy and a resume.
                                      Your task is to analyze the resume and determine if the candidate is a good fit for the job.
                                      The output should be in JSON format.
                                      Provide a score from 0 to 100 for the candidate's suitability for the job, and a detailed feedback.
                                      Response should only be Json that has the following keys: 'aiGeneratedScore', 'aiGeneratedFeedback'.
                                      Aigenerate feedback should be detailed and specific to the job and the candidate's resume."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please evalute this job application. Job Details: {$jobDetails}. Resume Details: {$resumeDetails}"
                    ]
                ],
                'response_format' => [
                    'type' => 'json_object'
                ],
                'temperature' => 0.1
            ]);

            $result = $response->choices[0]->message->content;
            Log::debug('OpenAI evaluationresponse: ' . $result);

            $parsedResult = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse OpenAI response: ' . json_last_error_msg());
                throw new \Exception('Failed to parse OpenAI response');
            }

            if(!isset($parsedResult['aiGeneratedScore']) || !isset($parsedResult['aiGeneratedFeedback'])) {
                Log::error('Missing required keys in the parsed result');
                throw new \Exception('Missing required keys in the parsed result');
            }

            return $parsedResult;

        } catch (\Exception $e) {
            Log::error('Error analyzing resume: ' . $e->getMessage());
            return [
                'aiGeneratedScore' => 0,
                'aiGeneratedFeedback' => 'An error occurred while analyzing the resume. Please try again later.'
            ];
        }


 }






    /**
     * Fallback parsing for non-JSON responses
     */
    private function parseNonJsonResponse(string $response): array
    {
        $defaultResult = ['summary' => '', 'skills' => '', 'experience' => '', 'education' => ''];

        // Try to extract sections using regex patterns
        $patterns = [
            'summary' => '/(?:summary|profile|about)[:\s]*([^\n]+(?:\n[^\n]+)*?)(?=skills|experience|education|$)/is',
            'skills' => '/(?:skills|technical skills|technologies|core competencies)[:\s]*([^\n]+(?:\n[^\n]+)*?)(?=summary|experience|education|$)/is',
            'experience' => '/(?:experience|work experience|employment|professional experience)[:\s]*([^\n]+(?:\n[^\n]+)*?)(?=summary|skills|education|$)/is',
            'education' => '/(?:education|academic background|qualifications)[:\s]*([^\n]+(?:\n[^\n]+)*?)(?=summary|skills|experience|$)/is',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $response, $matches)) {
                $defaultResult[$key] = trim($matches[1]);
                if (strlen($defaultResult[$key]) > 500) {
                    $defaultResult[$key] = substr($defaultResult[$key], 0, 500) . '...';
                }
            }
        }

        return $defaultResult;
    }


    /**
     * Convert value to string for database storage
     */
    private function convertToString($value): string
    {
        if (is_array($value)) {
            if (isset($value[0]) && is_string($value[0])) {
                return implode(', ', $value);
            }
            return json_encode($value);
        }

        return (string) $value;
    }

    /**
     * Get default empty result
     */
    private function getDefaultResult(): array
    {
        return [
            'summary' => '',
            'skills' => '',
            'experience' => '',
            'education' => '',
        ];
    }
}
