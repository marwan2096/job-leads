<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $vacancy->name }}
        </h2>
    </x-slot>
    {{-- //back butto --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('vacancy.index') }}" class="text-blue-500 hover:text-blue-700">← Back to Vacancies</a>
        </div>
    </div>

    <x-alert />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        {{-- Company Info Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <tbody>


                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Name</td>
                        <td class="px-6 py-4">{{ $vacancy->title }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">description</td>
                        <td class="px-6 py-4">{{ $vacancy->description }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Salary</td>
                        <td class="px-6 py-4">{{ $vacancy->salary }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Location</td>
                        <td class="px-6 py-4">{{ $vacancy->location }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Type</td>
                        <td class="px-6 py-4">{{ $vacancy->type }}</td>
                    </tr>
  <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Company</td>
                        <td class="px-6 py-4">{{ $vacancy->company->name }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Category</td>
                        <td class="px-6 py-4">{{ $vacancy->jobCategory?->name }}</td>
                    </tr>


                </tbody>
            </table>
        </div>

        {{-- edit archive --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('vacancy.edit', $vacancy) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Edit
            </a>
            <form action="{{ route('vacancy.destroy', $vacancy) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vacancy?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
              </div>
     {{-- jobs and applications tabs --}}
    <div class="mb-6">
                        <ul class="flex space-x-4">

                            <li>
                                <a href="{{ route('vacancy.show', ['vacancy' => $vacancy->id, 'tab' => 'applications']) }}"
                                    class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'applications' ? 'border-b-2 border-blue-500' : '' }}">Applications</a>
                            </li>
                        </ul>
                    </div>
     {{-- tab content --}}

   <div>

     <div id="applications" class="{{request('tab') == 'applications' || request('tab')==''  ? 'block' : 'hidden'}} ">
        <h3>Applications</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 uppercase">Applicant Name</th>
                            <th class="px-6 py-3 font-medium text-gray-500 uppercase">Job Title</th>
                            <th class="px-6 py-3 font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 font-medium text-gray-500 uppercase">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobapplications as $application)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $application->user?->name }}</td>
                                <td class="px-6 py-4">{{ $application->jobVacancy->title }}</td>
                                <td class="px-6 py-4">{{ $application->status }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('application.show', ['application' => $application->id]) }}" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

   </div>



</x-app-layout>
