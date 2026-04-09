<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Vacancy') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-2">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('vacancy.store') }}" method="POST">
                    @csrf
  {{-- 'title',
        'description',
        'location',
        'salary',
        'type',
        'category_id',
        'company_id', --}}
                    {{-- Vacancy Fields --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"> title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>



                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">salary</label>
                        <input type="number" name="salary" value="{{ old('salary') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                            <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">description</label>
                        <textarea name="description" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
    <label class="block text-sm font-medium text-gray-700 mb-1 mt-4">type</label>
                            <select name="type" id="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">

                                <option value="">Select Type</option>

                                <option value="Full-time" {{ old('type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Remote" {{ old('type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                                <option value="Hybrid" {{ old('type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="Part-time" {{ old('type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>

                            </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />


                             <label class="block text-sm font-medium text-gray-700 mb-1 mt-4">category</label>

                            <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />

                             <label class="block text-sm font-medium text-gray-700 mb-1 mt-4">company</label>

                            <select name="company_id" id="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>


                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />






        </div>

   {{-- Actions --}}
                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit"
                            class="bg-green-600 text-white text-sm font-medium px-5 py-2 rounded-lg">
                            Add Company
                        </button>
                        <a href="{{ route('company.index') }}" class="text-sm text-gray-500">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
