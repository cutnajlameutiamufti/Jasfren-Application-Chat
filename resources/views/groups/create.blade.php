<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-[calc(100vh-64px)] flex items-center justify-center font-sans">
        <div class="max-w-md w-full px-6">
            
            <!-- Tombol Kembali -->
            <div class="mb-4">
                <a href="{{ route('groups.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Grup
                </a>
            </div>

            <!-- Card Utama -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                <!-- Header Card -->
                <div class="px-6 py-6 bg-gradient-to-tr from-indigo-600 to-purple-600 text-white relative">
                    <h2 class="text-xl font-bold">Buat Grup Baru</h2>
                    <p class="text-xs text-indigo-100 mt-1">Undang teman-teman untuk berdiskusi bersama dalam satu ruang obrolan.</p>
                </div>

                <!-- Form Card -->
                <form action="{{ route('groups.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Nama Grup -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Nama Grup</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Keluarga, Alumni IT, Project Team" required autofocus
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        
                        @error('name')
                            <p class="mt-2 text-xs text-red-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pilih Anggota -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih Anggota</label>
                        
                        @error('members')
                            <p class="mb-3 text-xs text-red-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="bg-gray-50 border border-gray-200 rounded-xl max-h-60 overflow-y-auto divide-y divide-gray-100 p-2 space-y-1 font-sans">
                            @forelse($users as $u)
                                @php
                                    $initials = strtoupper(substr($u->name, 0, 2));
                                @endphp
                                <label class="flex items-center px-3 py-2.5 hover:bg-indigo-50/50 rounded-lg cursor-pointer transition-colors duration-150">
                                    <input type="checkbox" name="members[]" value="{{ $u->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3.5">
                                    
                                    <!-- Avatar -->
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white flex items-center justify-center font-bold text-xs shadow-sm shrink-0">
                                        {{ $initials }}
                                    </div>
                                    
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $u->name }}</p>
                                        <p class="text-[10px] text-gray-500">@&nbsp;{{ $u->username }}</p>
                                    </div>
                                </label>
                            @empty
                                <div class="py-6 text-center text-xs text-gray-400">
                                    Belum ada pengguna terdaftar untuk diundang.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 hover:shadow-indigo-500/35 hover:-translate-y-[1px] active:translate-y-[1px] transition-all duration-200">
                        Buat & Mulai Diskusi Grup
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
