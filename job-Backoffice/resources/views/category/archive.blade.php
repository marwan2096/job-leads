<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Categories') }}
        </h2>
    </x-slot>

<x-alert />


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase">name</th>

                        <th class="px-6 py-3 font-medium uppercase">actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium whitespace-nowrap">{{ $category->name }}</td>

                        <td class="px-6 py-4">

                            <form action="{{ route('category.restore', $category) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-green-500 hover:text-green-700">♻️ Restore</button>
                            </form>
                            <form action="{{ route('category.forceDelete', $category) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">🗃️ delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <a href="{{ route('category.index') }}" class="text-blue-500 hover:text-blue-700 flex justify-end mt-3 mr-4">
            ← Back to Categories
        </a>
        <div class="mt-6 flex justify-center">
            {{ $categories->links() }}
        </div>

    </div>

</x-app-layout>
