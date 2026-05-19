<x-app-layout>
    <div class="py-6 h-[calc(100vh-64px)] bg-gray-50">
        <div class="max-w-7xl mx-auto h-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl h-full border border-gray-100 flex">
                
                <!-- Kiri: Daftar Chat (Sidebar) -->
                <div class="hidden md:flex w-80 lg:w-96 border-r border-gray-100 flex-col h-full bg-white font-sans">
                    <!-- Header Daftar Chat -->
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Chat Personal</h1>
                            <p class="text-xs text-gray-500">Mulai obrolan dengan temanmu</p>
                        </div>
                        <a href="{{ route('chats.create') }}" class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors duration-200" title="Tambah Chat Baru">
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
                        @forelse($chats as $c)
                            @php
                                $cUser = $c['user'];
                                $cLastMsg = $c['last_message'];
                                $cUnread = $c['unread_count'];
                                $cInitials = strtoupper(substr($cUser->name, 0, 2));
                                $isActive = $cUser->id === $user->id;
                            @endphp
                            <a href="{{ route('chats.show', $cUser->username) }}" 
                               class="flex items-center px-4 py-3.5 hover:bg-blue-50/30 transition-all duration-200 group {{ $isActive ? 'bg-blue-50/50 border-l-4 border-blue-500 pl-3' : '' }}">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center font-semibold text-sm shadow-sm shrink-0">
                                    {{ $cInitials }}
                                </div>

                                <!-- Info Chat -->
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline">
                                        <h2 class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors duration-150 {{ $isActive ? 'text-blue-600' : '' }}">
                                            {{ $cUser->name }}
                                        </h2>
                                        <span class="text-xs text-gray-400">
                                            {{ $cLastMsg ? $cLastMsg->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-500 truncate pr-2">
                                            @if($cLastMsg)
                                                @if($cLastMsg->sender_id === auth()->id())
                                                    <span class="text-gray-400 font-medium">Anda:</span>
                                                @endif
                                                {{ $cLastMsg->body }}
                                            @else
                                                <span class="italic text-gray-400">Belum ada pesan</span>
                                            @endif
                                        </p>

                                        @if($cUnread > 0)
                                            <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 shadow-sm">
                                                {{ $cUnread }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-gray-400">@&nbsp;{{ $cUser->username }}</p>
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
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Kanan: Ruang Obrolan Aktif -->
                <div class="flex-1 flex flex-col h-full bg-gray-50/50">
                    <!-- Header Obrolan -->
                    <div class="px-6 py-4 bg-white border-b border-gray-100 flex justify-between items-center shrink-0">
                        <div class="flex items-center">
                            <!-- Tombol Back Mobile -->
                            <a href="{{ route('chats.index') }}" class="md:hidden p-1.5 mr-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>

                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>

                            <!-- Info User -->
                            <div class="ml-3">
                                <h2 class="text-sm font-bold text-gray-900 leading-tight">{{ $user->name }}</h2>
                                <div class="flex items-center mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    <span class="text-[10px] text-gray-500 ml-1.5">Online • @&nbsp;{{ $user->username }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Kanan Header (Visual Placeholder) -->
                        <div class="flex items-center space-x-1">
                            <button class="p-2 text-gray-400 hover:text-blue-600 rounded-full hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-600 rounded-full hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Area Pesan (Scrollable) -->
                    <div id="message-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                        <div id="messages-list" class="space-y-4">
                            @forelse($messages as $msg)
                                @php
                                    $isMe = $msg->sender_id === auth()->id();
                                @endphp
                                
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} animate-fade-in">
                                    <div class="max-w-[70%] lg:max-w-[60%] flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                                        <!-- Bubble -->
                                        <div class="px-4 py-2.5 rounded-2xl shadow-sm text-sm {{ $isMe ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none' }}">
                                            {{ $msg->body }}
                                        </div>
                                        <!-- Time -->
                                        <span class="text-[10px] text-gray-400 mt-1.5 px-1">
                                            {{ $msg->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full text-center p-8">
                                    <div class="w-16 h-16 bg-blue-100/40 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Mulai Percakapan</h3>
                                    <p class="text-xs text-gray-500 mt-1 max-w-xs">Kirim pesan pertama untuk memulai obrolan akrab dengan {{ $user->name }}!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Area Input Pesan (Form Placeholder) -->
                    <div class="px-6 py-4 bg-white border-t border-gray-100 shrink-0">
                        <form action="{{ route('chats.messages.store', $user->username) }}" method="POST" class="flex items-center space-x-3">
                            @csrf
                            <!-- Attachment Button (Visual Placeholder) -->
                            <button type="button" class="p-2.5 text-gray-400 hover:text-blue-600 rounded-full hover:bg-gray-50 transition-colors duration-200 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </button>

                            <!-- Input Text Field -->
                            <input type="text" name="body" placeholder="Tulis pesan..." required autocomplete="off"
                                   class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-200">

                            <!-- Send Button -->
                            <button type="submit" class="p-3 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/20 hover:bg-blue-700 hover:shadow-blue-500/35 transition-all duration-200 shrink-0">
                                <svg class="w-4 h-4 transform rotate-45 -translate-x-[1px] translate-y-[1px]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Script Real-Time Laravel Echo -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('message-container');
            const listEl = document.getElementById('messages-list');
            
            if (container) {
                container.scrollTop = container.scrollHeight;
            }

            // Daftarkan listener Echo
            if (window.Echo) {
                const authUserId = @json(auth()->id());
                const chatUserId = @json($user->id);

                window.Echo.private(`chat.user.${authUserId}`)
                    .listen('MessageSent', (e) => {
                        // Hanya tampilkan jika dikirim oleh lawan bicara yang aktif saat ini
                        if (parseInt(e.sender_id) === parseInt(chatUserId)) {
                            const messageHtml = `
                                <div class="flex justify-start animate-fade-in">
                                    <div class="max-w-[70%] lg:max-w-[60%] flex flex-col items-start">
                                        <!-- Bubble -->
                                        <div class="px-4 py-2.5 rounded-2xl shadow-sm text-sm bg-white text-gray-800 border border-gray-100 rounded-tl-none">
                                            ${e.body}
                                        </div>
                                        <!-- Time -->
                                        <span class="text-[10px] text-gray-400 mt-1.5 px-1">
                                            ${e.created_at}
                                        </span>
                                    </div>
                                </div>
                            `;

                            if (listEl) {
                                // Hapus empty state jika ada
                                const emptyState = listEl.querySelector('.text-center');
                                if (emptyState) {
                                    emptyState.remove();
                                }
                                
                                listEl.insertAdjacentHTML('beforeend', messageHtml);
                                container.scrollTop = container.scrollHeight;
                            }
                        }
                    });
            }
        });
    </script>
</x-app-layout>
