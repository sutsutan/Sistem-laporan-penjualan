<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Sistem Laporan Penjualan</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 min-h-screen">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">
        
        <!-- HEADER TOP BAR -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Dashboard Penjualan
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola Hak Akses Pengguna
                </h1>
                <p class="text-sm text-slate-500 mt-1">Hanya orang-orang tertentu yang terdaftar dan diaktifkan di sini yang dapat login ke sistem.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openCreateUserModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Pengguna
                </button>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-lg font-bold leading-none">&times;</button>
            </div>
        @endif

        <!-- NOTIFIKASI ERROR -->
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-lg font-bold leading-none">&times;</button>
            </div>
        @endif

        <!-- ERROR VALIDASI -->
        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm shadow-xs">
                <div class="flex items-center gap-2 font-semibold text-rose-900 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Terdapat kesalahan input:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- STATISTIK RINGKAS PENGGUNA -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Terdaftar</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $users->count() }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Pengguna di database</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs">
                <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Bisa Login (Aktif)</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $users->where('is_active', true)->count() }}</div>
                <div class="text-[11px] text-emerald-600/80 mt-0.5">Akses masuk terbuka</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs">
                <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Diblokir (Nonaktif)</div>
                <div class="text-2xl font-bold text-rose-700 mt-1">{{ $users->where('is_active', false)->count() }}</div>
                <div class="text-[11px] text-rose-600/80 mt-0.5">Akses masuk ditolak</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs">
                <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Administrator</div>
                <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $users->where('role', 'admin')->count() }}</div>
                <div class="text-[11px] text-indigo-600/80 mt-0.5">Memiliki kontrol penuh</div>
            </div>
        </div>

        <!-- TABEL PENGGUNA -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Daftar Pengguna Berwenang</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Hanya akun dengan status Aktif yang dapat login ke sistem.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-[11px] uppercase tracking-wider font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Status Akses</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900 flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if(auth()->id() === $user->id)
                                                    <span class="text-[10px] bg-blue-100 text-blue-800 font-semibold px-2 py-0.5 rounded-full">Akun Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-400 font-mono">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.789l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Administrator
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            Staff
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif (Bisa Login)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Nonaktif (Akses Ditolak)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(auth()->id() !== $user->id)
                                            <!-- TOGGLE STATUS AKTIF / NONAKTIF -->
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($user->is_active)
                                                    <button type="submit" onclick="return confirm('Nonaktifkan akses login untuk pengguna {{ $user->name }}?')" class="text-xs px-2.5 py-1 font-medium rounded-md bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" onclick="return confirm('Aktifkan izin login untuk pengguna {{ $user->name }}?')" class="text-xs px-2.5 py-1 font-medium rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                                                        Izinkan Akses
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- HAPUS PENGGUNA -->
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} dari sistem?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs px-2.5 py-1 font-medium rounded-md bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Akun Anda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL TAMBAH PENGGUNA BARU -->
    <div id="createUserModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tambah Pengguna Baru</h3>
                    <p class="text-xs text-slate-500">Berikan akses login kepada orang tertentu</p>
                </div>
                <button type="button" onclick="closeCreateUserModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none">&times;</button>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email (Untuk Login)</label>
                    <input type="email" id="email" name="email" required placeholder="budi@perusahaan.com"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi (Minimal 6 Karakter)</label>
                    <input type="password" id="password" name="password" minlength="6" required placeholder="••••••••"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran / Role</label>
                        <select id="role" name="role" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="staff" selected>Staff (Lihat/Edit Laporan)</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                    </div>
                    <div>
                        <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">Status Izin Login</label>
                        <select id="is_active" name="is_active" class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="1" selected>Aktif (Bisa Login)</option>
                            <option value="0">Nonaktif (Akses Ditolak)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium rounded-lg shadow-sm transition">Daftarkan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateUserModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }
        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
        }
    </script>
</body>
</html>
