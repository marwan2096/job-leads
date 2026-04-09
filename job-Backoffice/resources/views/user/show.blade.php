<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>
    {{-- //back butto --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('user.index') }}" class="text-blue-500 hover:text-blue-700">← Back to Users</a>
        </div>
    </div>

    <x-alert />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        {{-- User Info Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Name</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Email</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">Role</td>
                        <td class="px-6 py-4">{{ $user->role }}</td>
                    </tr>
                </tbody>





            </table>
        </div>

        {{-- edit archive --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('user.edit', $user) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Edit
            </a>
            <form action="{{ route('user.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                                <a href="{{ route('user.show', ['user' => $user->id, 'tab' => 'applications']) }}"
                                    class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'applications' ? 'border-b-2 border-blue-500' : '' }}">Applications</a>
                            </li>
                        </ul>
                    </div>
     {{-- tab content --}}

   <div>

    <div id="applications" class="{{request('tab') == 'applications'|| request('tab') === null ? 'block' : 'hidden'}} ">
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
                        @foreach($users as $userApplication)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $userApplication->user->name }}</td>
                                <td class="px-6 py-4">{{ $userApplication->jobVacancy?->title }}</td>
                                <td class="px-6 py-4">{{ $userApplication->status }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('application.show', ['application' => $userApplication->id]) }}" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

   </div>



</x-app-layout>
