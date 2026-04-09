
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Applications') }}
        </h2>
    </x-slot>

    <x-alert />


<div class="flex justify-between">

    <a href="{{ route('application.archiveView') }}" class=" mr-4 inline-block bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
        🗃️ Archived Applications
    </a>
</div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 border-b border-gray-200">
                    <tr>

                 <th class="px-6 py-3 font-medium uppercase">user_name</th>
                        <th class="px-6 py-3 font-medium uppercase">status</th>
                        <th class="px-6 py-3 font-medium uppercase">aiGeneratedScore</th>

                        <th class="px-6 py-3 font-medium uppercase">job_vacancy_name</th>
                        <th class="px-6 py-3 font-medium uppercase">resume_name</th>

                        <th class="px-6 py-3 font-medium uppercase">actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobApplication as $application)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">

             <td class="px-6 py-4 font-medium text-gray-900">
    <a href="{{ route('application.show', $application) }}" class="text-slate-600 hover:text-slate-900 hover:underline uppercase">
        {{ $application->user?->name }}
                <span class="text-xs text-gray-400 group-hover:text-gray-600">→ view details</span>
    </a>
</td>

<td class="px-5 py-4">
    <form action="{{ route('application.update', $application) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="status" onchange="this.form.submit()"
            class="text-sm border border-gray-300 rounded-lg px-7 py-5  cursor-pointer transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500
            {{ $application->status == 'accepted' ? 'text-green-600' : ($application->status == 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
            <option value="pending"  {{ $application->status == 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </form>
</td>
                        <td class="px-6 py-4 text-center">{{ $application->aiGeneratedScore }}</td>

                        <td class="px-6 py-4">{{ $application->jobVacancy->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4"> <span>{{ $application->resume->filename }} </span>   <a href="{{ Storage::disk('cloud')->url($application->resume->fileUri) }}" target="_blank"
                            class="text-indigo-500 hover:text-indigo-600">Download Resume</a></td>


                        <td class=" flex space-x-1 px-8 py-8">
                            <a href="{{ route('application.edit', $application) }}" class="text-blue-500 mr-2 hover:text-blue-700">✍️ Edit</a>
                            <form action="{{ route('application.destroy', $application) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">🗃️ Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $jobApplication->links() }}
        </div>

    </div>

</x-app-layout>
