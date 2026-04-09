<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $application->user->name }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('application.index') }}" class="text-blue-500 hover:text-blue-700">← Back to Applications</a>
        </div>
    </div>

    <x-alert />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        {{-- Application Info Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Name</td>
                        <td class="px-6 py-4">{{ $application->user->name }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Status</td>
                        <td class="px-6 py-4">{{ $application->status }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">AI Generated Score</td>
                        <td class="px-6 py-4">{{ $application->aiGeneratedScore }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">AI Generated Feedback</td>
                        <td class="px-6 py-4">{{ $application->aiGeneratedFeedback }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Job Vacancy</td>
                        <td class="px-6 py-4">{{ $application->jobVacancy?->title ?? 'N/A' }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Resume</td>
                        <td class="px-6 py-4">{{ $application->resume?->filename ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('application.edit', $application) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Edit
            </a>
            <form action="{{ route('application.destroy', $application) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this application?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>

        {{-- Tabs --}}
        <div class="mb-6">
            <ul class="flex space-x-4">
                <li>
                    <a href="{{ route('application.show', ['application' => $application->id, 'tab' => 'resume']) }}"
                        class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'resume' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">
                        Resume
                    </a>
                </li>
                <li>
                    <a href="{{ route('application.show', ['application' => $application->id, 'tab' => 'feedback']) }}"
                        class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'feedback' ? 'border-b-2 border-blue-500' : '' }}">
                        Feedback
                    </a>
                </li>
            </ul>
        </div>

        {{-- Tab Content --}}
        <div>

            {{-- Resume Tab --}}
         {{-- Resume Tab --}}
<div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block' : 'hidden' }}">
    <h3 class="text-lg font-semibold mb-3">Resume</h3>
    <div class="rounded-lg border border-gray-200 shadow-sm divide-y divide-gray-200">

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">File</p>
            <p class="text-sm text-gray-800">{{ $application->resume?->fileUri ?? 'N/A' }}</p>
        </div>

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">Contact Details</p>
            <p class="text-sm text-gray-800">{{ $application->resume?->contactDetails ?? 'N/A' }}</p>
        </div>

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">Summary</p>
            <p class="text-sm text-gray-800">{{ $application->resume?->summary ?? 'N/A' }}</p>
        </div>

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">Skills</p>
            <p class="text-sm text-gray-800">{{ $application->resume?->skills ?? 'N/A' }}</p>
        </div>

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">Experience</p>
            <p class="text-sm text-gray-800 whitespace-pre-line">{{ $application->resume?->experience ?? 'N/A' }}</p>
        </div>

        <div class="px-6 py-4 hover:bg-gray-50">
            <p class="text-sm font-medium text-blue-500 uppercase mb-1">Education</p>
            <p class="text-sm text-gray-800 whitespace-pre-line">{{ $application->resume?->education ?? 'N/A' }}</p>
        </div>

    </div>
</div>
            {{-- Feedback Tab --}}
            <div id="feedback" class="{{ request('tab') == 'feedback' ? 'block' : 'hidden' }}">
                <h3 class="text-lg font-semibold mb-3">Feedback</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-500 uppercase">AI Generated Score</th>
                                <th class="px-6 py-3 font-medium text-gray-500 uppercase">AI Generated Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $application->status }}</td>
                                <td class="px-6 py-4">{{ $application->aiGeneratedScore }}</td>
                                <td class="px-6 py-4">{{ $application->aiGeneratedFeedback }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> {{-- end tab content --}}

    </div> {{-- end max-w-7xl --}}

</x-app-layout>
