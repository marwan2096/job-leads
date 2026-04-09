<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Category') }}
        </h2>
    </x-slot>



    <div class="py-8">
        <div class="max-w-xl mx-auto px-2">


            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter category name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg">
                            Add Category
                        </button>
                        <a href="{{ route('category.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-app-layout>
