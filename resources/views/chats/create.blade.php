<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-[calc(100vh-64px)] flex items-center justify-center font-sans">
        <div class="max-w-md w-full px-6">
            
            <!-- Tombol Kembali -->
            <div class="mb-4">
                <a href="{{ route('chats.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Chat
                </a>
            </div>

            <!-- Card Utama -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                <!-- Header Card -->
                <div class="px-6 py-6 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white relative">
                    <h2 class="text-xl font-bold">Mulai Chat Baru</h2>
                    <p class="text-xs text-blue-100 mt-1">Cari teman Anda berdasarkan username unik mereka.</p>
                </div>

                <!-- Form Card -->
                <form action="{{ route('chats.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Username Teman</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 font-medium">
                                @
                            </span>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="username" required autofocus
                                class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-200 @error('username') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        
                        @error('username')
                            <p class="mt-2 text-xs text-red-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 hover:shadow-blue-500/35 hover:-translate-y-[1px] active:translate-y-[1px] transition-all duration-200">
                        Cari & Mulai Obrolan
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
