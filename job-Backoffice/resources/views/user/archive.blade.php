<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Users') }}
        </h2>
    </x-slot>

    <x-alert />

    <div class="flex justify-between">
        <a href="{{ route('user.index') }}"
            class="mr-4 inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
            ← Back to Users
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase">Name</th>
                        <th class="px-6 py-3 font-medium uppercase">Email</th>
                        <th class="px-6 py-3 font-medium uppercase">Role</th>
                        <th class="px-6 py-3 font-medium uppercase">Deleted At</th>
                        <th class="px-6 py-3 font-medium uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4">{{ $user->email }}</td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-medium px-2 py-1 rounded-lg
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' :
                                   ($user->role == 'company_owner' ? 'bg-blue-100 text-blue-700' :
                                   'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $user->deleted_at?->diffForHumans() }}
                        </td>

                        <td class="flex space-x-1 px-8 py-8">
                            <form action="{{ route('user.restore', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-green-500 hover:text-green-700">♻️ Restore</button>
                            </form>
                            <form action="{{ route('user.forceDelete', $user) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Permanently delete this user?')">
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
            {{ $users->links() }}
        </div>

    </div>

</x-app-layout>
