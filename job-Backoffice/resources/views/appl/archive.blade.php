<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Applications') }}
        </h2>
    </x-slot>

    <x-alert />

    <div class="flex justify-between">
        <a href="{{ route('application.index') }}" class="mr-4 inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
            ← Back to Applications
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase">User</th>
                        <th class="px-6 py-3 font-medium uppercase">Status</th>
                        <th class="px-6 py-3 font-medium uppercase">AI Score</th>
                        <th class="px-6 py-3 font-medium uppercase">Job Vacancy</th>
                        <th class="px-6 py-3 font-medium uppercase">Resume</th>
                        <th class="px-6 py-3 font-medium uppercase">Deleted At</th>
                        <th class="px-6 py-3 font-medium uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium text-gray-900">
                            <a href="{{ route('application.show', $application) }}"
                               class="text-slate-600 hover:text-slate-900 hover:underline uppercase">
                                {{ $application->user->name }}
                                <span class="text-xs text-gray-400">→ view details</span>
                            </a>
                        </td>

                        <td class="px-5 py-4">
                            <span class="text-sm font-medium px-2 py-1 rounded-lg
                                {{ $application->status == 'accepted' ? 'bg-green-100 text-green-700' : ($application->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">{{ $application->aiGeneratedScore ?? 'N/A' }}</td>

                        <td class="px-6 py-4">{{ $application->jobVacancy?->title ?? 'N/A' }}</td>

                        <td class="px-6 py-4">{{ $application->resume?->filename ?? 'N/A' }}</td>

                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $application->deleted_at?->diffForHumans() }}</td>

                        <td class="flex space-x-1 px-8 py-8">
                            <form action="{{ route('application.restore', $application) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-green-500 hover:text-green-700">♻️ Restore</button>
                            </form>
                            <form action="{{ route('application.forceDelete', $application) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Permanently delete this application?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 ml-2">🗑️ Delete</button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $applications->links() }}
        </div>

    </div>

</x-app-layout>
