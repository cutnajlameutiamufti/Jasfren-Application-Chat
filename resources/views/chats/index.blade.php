<x-app-layout>
    <div class="py-6 h-[calc(100vh-64px)] bg-gray-50">
        <div class="max-w-7xl mx-auto h-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl h-full border border-gray-100 flex">
                
                <!-- Kiri: Daftar Chat -->
                <div class="w-full md:w-80 lg:w-96 border-r border-gray-100 flex flex-col h-full bg-white font-sans">
                    <!-- Header Daftar Chat -->
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Chat Personal</h1>
                            <p class="text-xs text-gray-500">Mulai obrolan dengan temanmu</p>
                        </div>
                        <a href="#" class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors duration-200" title="Tambah Chat Baru">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Search / Filter (Visual Placeholder) -->
                    <div class="p-3 border-b border-gray-50">
                        <div class="relative">
                            <input type="text" placeholder="Cari obrolan..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Daftar Obrolan -->
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-50">
                        @forelse($chats as $chat)
                            @php
                                $user = $chat['user'];
                                $lastMsg = $chat['last_message'];
                                $unread = $chat['unread_count'];
                                $initials = strtoupper(substr($user->name, 0, 2));
                            @endphp
                            <a href="#" class="flex items-center px-4 py-3.5 hover:bg-blue-50/30 transition-all duration-200 group">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center font-semibold text-sm shadow-sm shrink-0">
                                    {{ $initials }}
                                </div>

                                <!-- Info Chat -->
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline">
                                        <h2 class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors duration-150">
                                            {{ $user->name }}
                                        </h2>
                                        <span class="text-xs text-gray-400">
                                            {{ $lastMsg ? $lastMsg->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-500 truncate pr-2">
                                            @if($lastMsg)
                                                @if($lastMsg->sender_id === auth()->id())
                                                    <span class="text-gray-400 font-medium">Anda:</span>
                                                @endif
                                                {{ $lastMsg->body }}
                                            @else
                                                <span class="italic text-gray-400">Belum ada pesan</span>
                                            @endif
                                        </p>

                                        @if($unread > 0)
                                            <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 shadow-sm">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-gray-400">@&nbsp;{{ $user->username }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center px-6 py-12">
                                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">Belum Ada Obrolan</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-[200px]">Mulai obrolan baru dengan mencari teman berdasarkan username.</p>
                                <a href="#" class="mt-4 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition-all duration-200">
                                    Tambah Chat Baru
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Kanan: Ruang Obrolan Default (Empty State) -->
                <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-gray-50/50 p-8 text-center h-full">
                    <div class="max-w-md flex flex-col items-center">
                        <div class="w-24 h-24 bg-blue-100/50 text-blue-600 rounded-full flex items-center justify-center shadow-inner mb-6 animate-pulse">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Jasfren Chat</h2>
                        <p class="text-sm text-gray-500 mt-2 max-w-sm">Pilih teman dari daftar obrolan di sebelah kiri untuk mulai berkirim pesan secara real-time.</p>
                        <div class="mt-8 flex items-center space-x-2 text-xs text-gray-400">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>Terinkripsi secara privat</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
