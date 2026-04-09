<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-2">
            <div class="bg-white rounded-lg shadow p-6">

                <form method="POST" action="{{ route('user.update', $user) }}">
                    @csrf
                    @method('PUT')

                    {{-- Password --}}
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('New Password')" />

                        <div class="relative" x-data="{ showPassword: false }">
                            <x-text-input id="password"
                                class="block mt-1 w-full p-2"
                                name="password"
                                autocomplete="new-password"
                                x-bind:type="showPassword ? 'text' : 'password'" />

                            <button @click="showPassword = !showPassword" type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2">

                                {{-- Eye open --}}
                                <svg x-show="!showPassword" width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.0012 5C7.52354 5 3.73326 7.94288 2.45898 12C3.73324 16.0571 7.52354 19 12.0012 19C16.4788 19 20.2691 16.0571 21.5434 12C20.2691 7.94291 16.4788 5 12.0012 5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                {{-- Eye closed --}}
                                <svg x-show="showPassword" width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full p-2"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password" />
                    </div>
                  <div class="mt-4">
                                        <select name="role" id="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
       <option value="" disabled {{ old('role', $user->role) ? '' : 'selected' }}>Select Role</option>
    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
    <option value="job_seeker" {{ old('role', $user->role) == 'job_seeker' ? 'selected' : '' }}>Job Seeker</option>
    <option value="company_owner" {{ old('role', $user->role) == 'company_owner' ? 'selected' : '' }}>Company Owner</option>
</select>
                  </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('user.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 mr-4">
                            Cancel
                        </a>
                        <x-primary-button>
                            {{ __('Update Password') }}
                        </x-primary-button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</x-app-layout>
