   <x-app-layout>
       <x-slot name="header">
           <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Users') }}
           </h2>
       </x-slot>


       <x-alert />


       <div class="flex justify-between">

           <a href="{{ route('user.archiveView') }}"
               class=" mr-4 inline-block bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded mt-3 ml-4">
               🗃️ Archived Users
           </a>
       </div>

       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

           <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
               <table class="w-full text-sm text-left">
                   <thead class="bg-indigo-100 border-b border-gray-200">
                       <tr>
                           <th class="px-6 py-3 font-medium uppercase">name</th>

                           <th class="px-6 py-3 font-medium uppercase">email</th>
                           <th class="px-6 py-3 font-medium uppercase">role</th>

                           <th class="px-6 py-3 font-medium uppercase">created_at</th>
                           <th class="px-6 py-3 font-medium uppercase">actions</th>
                       </tr>
                   </thead>
                   <tbody>
                       @foreach ($users as $user)
                           @if ((auth()->user()->role === 'admin' || $user->role !== 'admin'))
                               <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-6 py-4 font-medium text-gray-900">
    <a href="{{ route('user.show', $user) }}" class="text-slate-600 hover:text-slate-900 hover:underline uppercase">
        {{ $user->name }}
                <span class="text-xs text-gray-400 group-hover:text-gray-600">→ view details</span>
    </a>
</td>


                                   <td class="px-6 py-4">{{ $user->email }}</td>
                                   <td class="px-6 py-4">{{ $user->role }}</td>

                                   <td class="px-6 py-4">{{ $user->created_at }}</td>
                                   <td class="px-6 py-4">
                                       <a href="{{ route('user.edit', $user) }}"
                                           class="text-blue-500 mr-2 hover:text-blue-700">✍️ Edit</a>
                                       <form action="{{ route('user.destroy', $user) }}" method="POST"
                                           class="inline-block">
                                           @csrf
                                           @method('DELETE')
                                           <button type="submit" class="text-red-500 hover:text-red-700">🗃️
                                               delete</button>
                                       </form>

                                   </td>

                               </tr>
                           @endif
                       @endforeach

                   </tbody>
               </table>
           </div>

           <div class="mt-6 flex justify-center">
               {{ $users->links() }}
           </div>

       </div>

   </x-app-layout>
