<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <h3 class="text-white text-2xl font-bold mb-6">
                {{ __('Welcome back,') }} {{ Auth::user()->name }}!
            </h3>

            <!-- Search & Filters -->
            <div class="flex items-center justify-between">
                <!-- Search Bar -->
                <!-- Search by Title -->

                <form action="{{ route('dashboard') }}" method="get" class="flex items-center gap-2">

                    <input type="text" name="search" value="{{ request('search') }}"
                        class="p-2 rounded-lg bg-gray-800 text-white" placeholder="Search by title...">

                    <input type="text" name="location" value="{{ request('location') }}"
                        class="p-2 rounded-lg bg-gray-800 text-white" placeholder="Search by location...">

                    <input type="text" name="company" value="{{ request('company') }}"
                        class="p-2 rounded-lg bg-gray-800 text-white" placeholder="Search by company...">

                    @if (request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif

                    <button type="submit" class="bg-indigo-500 text-white p-2 rounded-lg">Search</button>

                    @if (request('search') || request('location') || request('company'))
                        <a href="{{ route('dashboard', ['filter' => request('filter')]) }}"
                            class="text-white p-2 rounded-lg">Clear</a>
                    @endif

                </form>


                <!-- Filters -->
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard', ['filter' => 'Full-Time', 'search' => request('search'), 'location' => request('location'), 'company' => request('company')]) }}"
                        class="p-2 rounded-lg text-white font-bold {{ request('filter') === 'Full-Time' ? 'bg-indigo-700' : 'bg-indigo-400' }}">
                        Full-Time
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'Remote', 'search' => request('search'), 'location' => request('location'), 'company' => request('company')]) }}"
                        class="p-2 rounded-lg text-white font-bold {{ request('filter') === 'Remote' ? 'bg-indigo-700' : 'bg-indigo-400' }}">
                        Remote
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'Hybrid', 'search' => request('search'), 'location' => request('location'), 'company' => request('company')]) }}"
                        class="p-2 rounded-lg text-white font-bold {{ request('filter') === 'Hybrid' ? 'bg-indigo-700' : 'bg-indigo-400' }}">
                        Hybrid
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'Contract', 'search' => request('search'), 'location' => request('location'), 'company' => request('company')]) }}"
                        class="p-2 rounded-lg text-white font-bold {{ request('filter') === 'Contract' ? 'bg-indigo-700' : 'bg-indigo-400' }}">
                        Contract
                    </a>

                    @if (request('filter'))
                        <a href="{{ route('dashboard', ['search' => request('search'), 'location' => request('location'), 'company' => request('company')]) }}"
                            class=" text-white p-2 rounded-lg">Clear</a>
                    @endif
                </div>
            </div>
            <!-- Job Listings -->
            <div class="space-y-4 mt-6">
                @forelse ($jobs as $job)
                    <!-- Job Item -->
                    <div class="border-b border-white/10 pb-4 flex justify-between items-center">
                        <div>
                            <a href="{{ route('vacancy.show', $job->id) }}"
                                class="text-lg font-semibold text-blue-400 hover:underline">{{ $job->title }}</a>
                            <p class="text-sm text-white">{{ $job->company->name }} - {{ $job->location }}</p>
                            <p class="text-sm text-white">{{ '$' . number_format($job->salary) }} / Year</p>
                        </div>
                        <span class="bg-blue-500 text-white p-2 rounded-lg">{{ $job->type }}</span>
                    </div>
                @empty
                    <p class="text-white text-2xl font-bold">No jobs found!</p>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        </div>
</x-app-layout>
