<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Admin - Users') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-2">
                    @php
                        $filters = ['all' => 'All', 'customer' => 'Customers', 'rider' => 'Riders', 'admin' => 'Admins'];
                    @endphp
                    @foreach ($filters as $value => $label)
                        <button
                            type="submit"
                            name="role"
                            value="{{ $value === 'all' ? '' : $value }}"
                            class="px-4 py-2 rounded-full text-sm border {{ $selectedRole === $value ? 'bg-[#F15B4E] text-white border-[#F15B4E]' : 'text-slate-600 border-slate-200 bg-white' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Users</h3>

                    <div class="space-y-3">
                        @forelse ($users as $user)
                            <div class="border border-slate-200 rounded-xl px-4 py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-slate-800 text-white' : ($user->role === 'rider' ? 'bg-[#ffe3df] text-[#F15B4E]' : 'bg-slate-100 text-slate-600') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm">No users found for this filter.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Rider Directory</h3>
                    <div class="space-y-2">
                        @forelse ($riders as $rider)
                            <div class="border border-slate-200 rounded-xl px-3 py-2">
                                <p class="text-sm font-medium text-slate-800">{{ $rider->name }}</p>
                                <p class="text-xs text-slate-500">{{ $rider->email }}</p>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm">No riders yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
