
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vacancy') }}
        </h2>
    </x-slot>

    <x-alert />


<div class="flex justify-between">
    <a href="{{ route('vacancy.create') }}" class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
        ➕ Add Vacancy
    </a>
    <a href="{{ route('vacancy.archiveView') }}" class=" mr-4 inline-block bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
        🗃️ Archived Vacancies
    </a>
</div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 border-b border-gray-200">
                    <tr>

                        <th class="px-6 py-3 font-medium uppercase">title</th>
                        <th class="px-6 py-3 font-medium uppercase">description</th>
                        <th class="px-6 py-3 font-medium uppercase">location</th>
                        <th class="px-6 py-3 font-medium uppercase">salary</th>
                        <th class="px-6 py-3 font-medium uppercase">type</th>
                        <th class="px-6 py-3 font-medium uppercase">category</th>
                        <th class="px-6 py-3 font-medium uppercase">company</th>
                        <th class="px-6 py-3 font-medium uppercase">actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacancies as $vacancy)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                       <td class="px-6 py-4 font-medium text-gray-900">
    <a href="{{ route('vacancy.show', $vacancy) }}" class="text-slate-600 hover:text-slate-900 hover:underline uppercase">
        {{ $vacancy->title }}
                <span class="text-xs text-gray-400 group-hover:text-gray-600">→ view details</span>
    </a>
</td>

                        </a>

                        <td class="px-6 py-4">{{ Str::limit($vacancy->description, 50) }}</td>
                        <td class="px-6 py-4">{{ $vacancy->location }}</td>
                        <td class="px-6 py-4">{{ $vacancy->salary }}</td>
                        <td class="px-6 py-4">{{ $vacancy->type }}</td>
                        <td class="px-6 py-4">{{ $vacancy->JobCategory->name ?? 'N/A' }}</td>
                        <td class="px-6 py">{{ $vacancy->Company->name ?? 'N/A' }}</td>


                        <td class=" flex space-x-1 px-6 py-4">
                            <a href="{{ route('vacancy.edit', $vacancy) }}" class="text-blue-500 mr-2 hover:text-blue-700">✍️ Edit</a>
                            <form action="{{ route('vacancy.destroy', $vacancy) }}" method="POST" class="inline-block">
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
            {{ $vacancies->links() }}
        </div>

    </div>

</x-app-layout>
