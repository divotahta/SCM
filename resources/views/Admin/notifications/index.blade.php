<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Notifikasi</h3>
                        <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Tandai Semua Dibaca
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse ($notifications as $notification)
                            <div class="bg-white p-4 rounded-lg shadow {{ $notification->dibaca ? 'opacity-75' : '' }}" id="notification-{{ $notification->id }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <a href="{{ $notification->link }}" class="block hover:bg-gray-50 -m-4 p-4 rounded-lg transition-colors duration-150">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $notification->judul }}</h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ $notification->pesan }}</p>
                                            <p class="mt-2 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    </div>
                                    @if (!$notification->dibaca)
                                        <button onclick="markAsRead({{ $notification->id }})" class="ml-4 text-sm text-indigo-600 hover:text-indigo-900">
                                            Tandai Dibaca
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500">Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function markAsRead(id) {
            fetch(`/admin/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = document.getElementById(`notification-${id}`);
                    notification.classList.add('opacity-75');
                    notification.querySelector('button').remove();
                    updateNotificationCount();
                }
            });
        }

        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }

        function updateNotificationCount() {
            fetch('/admin/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        if (data.count === 0) {
                            badge.classList.add('hidden');
                        }
                    }
                });
        }
    </script>
</x-app-layout> 