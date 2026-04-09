<x-guest-layout>
    <div class="text-center">
        <h2 class="text-xl font-bold text-red-600">Unauthorized Access</h2>
        <p class="mt-2 text-gray-600">You don't have permission to view this page.</p>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="mt-4 inline-block text-indigo-600 underline">
        Back to Login
    </button>
</form>
    </div>
</x-guest-layout>
