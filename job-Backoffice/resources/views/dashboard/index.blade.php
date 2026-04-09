<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-1">Overview</p>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Dashboard</h2>
            </div>
            <div class="text-sm text-gray-400">{{ now()->format('l, d M Y') }}</div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                {{-- Active Users --}}
                <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden group hover:shadow-md transition-shadow duration-200">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full opacity-60 group-hover:opacity-100 transition-opacity duration-200"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500"> Users</span>
                    </div>
                    <p class="text-4xl font-bold text-gray-900">{{ $analytics['active_users'] }}</p>
                   
                </div>

                {{-- Total Jobs --}}
                <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden group hover:shadow-md transition-shadow duration-200">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full opacity-60 group-hover:opacity-100 transition-opacity duration-200"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A8.003 8.003 0 0112 21 8 8 0 014 13V7a2 2 0 012-2h12a2 2 0 012 2v6.255z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">Total Jobs</span>
                    </div>
                    <p class="text-4xl font-bold text-gray-900">{{ $analytics['total_jobs'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">All time</p>
                </div>

                {{-- Total Applications --}}
                <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden group hover:shadow-md transition-shadow duration-200">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-bl-full opacity-60 group-hover:opacity-100 transition-opacity duration-200"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">Total Applications</span>
                    </div>
                    <p class="text-4xl font-bold text-gray-900">{{ $analytics['total_applications'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">All time</p>
                </div>

            </div>

            {{-- Tables Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Most Applied Jobs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">Most Applied Jobs</h3>
                    </div>
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3 text-left">Job Title</th>
                                <th class="px-6 py-3 text-left">Company</th>
                                <th class="px-6 py-3 text-left">Location</th>
                                <th class="px-6 py-3 text-right">Applications</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($analytics['most_applied_jobs'] as $job)
                                <tr class="hover:bg-gray-50 transition-colors duration-100">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $job->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $job->company->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $job->location }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $job->applications_count }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Top Converting Job Posts --}}



                        </tbody>
                    </table>
                </div>

                {{-- Top Converting Job Posts --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">Top Converting Job Posts</h3>
                    </div>
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3 text-left">Job Title</th>
                                <th class="px-6 py-3 text-right">Views</th>
                                <th class="px-6 py-3 text-right">Applications</th>
                                <th class="px-6 py-3 text-right">Conversion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($analytics['conversion_rates'] as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-100">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $item->view_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $item->applications_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $item->conversion_rate }}%
                                </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
