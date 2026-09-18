<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laporan Penjualan</title>
<<<<<<< HEAD
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

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 space-y-8">
        
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Laporan Penjualan</h1>
                <p class="text-sm text-slate-500 mt-1">Rekapitulasi penjualan dan manajemen entri laporan terbaru</p>
            </div>
            <div>
                <button type="button" onclick="openReportModal()" id="btnCreateNewTable" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create New Table
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

        <!-- SECTION 1: AKUMULASI PENJUALAN (CLEAN STAT CARDS) -->
        <div>
            <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Akumulasi Penjualan</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <!-- Qty Cash -->
                <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Qty Cash</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalCash }}</div>
                </div>

                <!-- Qty Kredit -->
                <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Qty Kredit</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalKredit }}</div>
                </div>

                <!-- Qty Instansi -->
                <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Qty Instansi</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalInstansi }}</div>
                </div>

                <!-- Total Qty -->
                <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Qty</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-800"></span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalAll }}</div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: LAPORAN TERBARU (MODERN SIMPLE TABLE) -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Laporan Terbaru</h2>
                    <p class="text-xs text-slate-500">Daftar entri laporan penjualan yang tercatat dalam sistem</p>
                </div>
                <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200/60">
                    {{ $recentReports->count() }} Laporan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4 text-center w-14">No</th>
                            <th class="py-3 px-4">Nama Laporan</th>
                            <th class="py-3 px-4 w-44">Tanggal Pembuatan</th>
                            <th class="py-3 px-4 text-center w-32">Kategori</th>
                            <th class="py-3 px-4 text-center w-24">Qty</th>
                            <th class="py-3 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recentReports as $index => $report)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-medium text-slate-900">{{ $report->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($report->category === 'cash')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        Cash
                                    </span>
                                @elseif($report->category === 'kredit')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/70">
                                        Kredit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/70">
                                        Instansi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-800">
                                {{ $report->qty }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Edit -->
                                    <button type="button" 
                                            onclick="openEditModal({{ $report->id }}, '{{ addslashes($report->name) }}', '{{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}', '{{ $report->category }}', {{ $report->qty }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 rounded-md transition shadow-2xs"
                                            title="Edit Laporan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Form & Tombol Delete -->
                                    <form action="{{ route('sales.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan \'{{ addslashes($report->name) }}\'?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-md transition shadow-2xs"
                                                title="Hapus Laporan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="text-sm">Belum ada data laporan yang dibuat.</p>
                                <button type="button" onclick="openReportModal()" class="mt-2 text-xs font-medium text-blue-600 hover:text-blue-800 transition">
                                    + Buat Laporan Pertama
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL: CREATE NEW TABLE -->
    <div id="reportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-base font-semibold text-slate-900">Create New Table</h3>
                <button type="button" onclick="closeReportModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none transition">&times;</button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('sales.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Nama Laporan -->
                <div>
                    <label for="nameInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nama Laporan</label>
                    <input type="text" id="nameInput" name="name" value="{{ old('name') }}" placeholder="Contoh: Laporan Penjualan September" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                </div>

                <!-- Tanggal Pembuatan Laporan -->
                <div>
                    <label for="dateInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Tanggal Pembuatan</label>
                    <input type="date" id="dateInput" name="report_date" value="{{ old('report_date', date('Y-m-d')) }}" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                </div>

                <!-- Kategori Laporan -->
                <div>
                    <label for="categoryInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kategori Laporan</label>
                    <select id="categoryInput" name="category" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="cash" {{ old('category') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="kredit" {{ old('category') === 'kredit' ? 'selected' : '' }}>Kredit</option>
                        <option value="instansi" {{ old('category') === 'instansi' ? 'selected' : '' }}>Instansi</option>
                    </select>
                </div>

                <!-- Jumlah (Qty) dengan stepper bersih -->
                <div>
                    <label for="createQtyInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Jumlah (Qty)</label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-white">
                            <button type="button" onclick="adjustQty('createQtyInput', -1)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-base transition border-r border-slate-200 select-none">-</button>
                            <input type="number" id="createQtyInput" name="qty" value="{{ old('qty', 1) }}" min="0" class="w-20 text-center py-1.5 text-sm font-semibold text-slate-900 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
                            <button type="button" onclick="adjustQty('createQtyInput', 1)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-base transition border-l border-slate-200 select-none">+</button>
                        </div>
                        <span class="text-xs text-slate-400">Bisa diketik angka bebas (0 - &infin;)</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2 rounded-lg text-xs font-medium shadow-xs transition">
                        Create New Table
                    </button>
                </div>
=======
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen pb-12">

    <!-- NAVBAR TOP BAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-md shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 leading-tight">Sales Analytics</h1>
                    <p class="text-xs text-slate-500">Sistem Rekapitulasi Penjualan Harian</p>
                </div>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg border border-slate-200">
                v2.0 System Active
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-8">
        
        <!-- ALERT NOTIFIKASI -->
        @if(session('success'))
            <div class="flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm transition-all">
                <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- FILTER TANGGAL PANEL -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Filter Laporan Penjualan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan tanggal transaksi untuk menampilkan tabel data rekapitulasi.</p>
                </div>
                
                <form action="{{ route('sales.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <input type="date" name="date" value="{{ $date }}" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-medium outline-none transition" required>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-indigo-100 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Tampilkan Tabel
                    </button>
                    
                    @if($date)
                        <a href="{{ route('sales.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- FITUR BARU: DAFTAR TANGGAL TERSEDIA -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">🔍 Tanggal Tersedia Yang Memiliki Transaksi:</span>
                <div class="flex flex-wrap gap-2">
                    @forelse($availableDates as $item)
                        <a href="{{ route('sales.index', ['date' => $item->date]) }}" 
                           class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-medium border transition-all {{ $date == $item->date ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200' }}">
                            📅 {{ date('d M Y', strtotime($item->date)) }}
                            <span class="ml-2 bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $date == $item->date ? 'bg-white text-indigo-700' : '' }}">
                                {{ $item->total_tx }} Tx
                            </span>
                        </a>
                    @empty
                        <span class="text-xs text-slate-400 italic">Belum ada transaksi tersimpan di database.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- HASIL TABEL REKAPITULASI -->
        @if($date)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">Laporan Aktif</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-2">
                            Rekapitulasi: <span class="text-indigo-600">{{ date('d F Y', strtotime($date)) }}</span>
                        </h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nama Produk</th>
                                <th scope="col" class="px-6 py-4 text-center">Qty Cash</th>
                                <th scope="col" class="px-6 py-4 text-center">Qty Kredit</th>
                                <th scope="col" class="px-6 py-4 text-center">Qty Instansi</th>
                                <th scope="col" class="px-6 py-4 text-center bg-slate-100/70 text-slate-800">Total Terjual</th>
                                <th scope="col" class="px-6 py-4 text-center">Aksi Management</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $p)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $p->name }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-emerald-600">
                                    <span class="inline-block bg-emerald-50 px-2.5 py-1 rounded-lg">{{ $p->total_cash }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-amber-600">
                                    <span class="inline-block bg-amber-50 px-2.5 py-1 rounded-lg">{{ $p->total_kredit }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-sky-600">
                                    <span class="inline-block bg-sky-50 px-2.5 py-1 rounded-lg">{{ $p->total_instansi }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-900 bg-slate-50/50">
                                    <span class="text-base">{{ $p->total_cash + $p->total_kredit + $p->total_instansi }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal({{ $p->id }}, '{{ $p->name }}')" class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-medium transition shadow-sm active:scale-95">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit Qty
                                        </button>

                                        <!-- Hapus Button -->
                                        <form action="{{ route('sales.destroy') }}" method="POST" onsubmit="return confirm('Yakin menghapus produk {{ $p->name }} dari sistem?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    Belum ada transaksi di tanggal ini. Input transaksi baru di bagian bawah.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- STATE KOSONG SBLM PILIH TANGGAL -->
            <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-300 shadow-sm">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tabel Laporan Belum Dipilih</h3>
                <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">Pilih tanggal dari panel di atas atau klik salah satu <strong>Tanggal Tersedia</strong> untuk membuka tabelnya.</p>
            </div>
        @endif

        <!-- FORM INPUT PANELS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- FORM 1: TAMBAH PRODUK -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-emerald-100 text-emerald-600 p-2 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Tambah Produk Baru</h2>
                    </div>

                    <form action="{{ route('sales.product.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nama Produk</label>
                            <input type="text" name="name" placeholder="Contoh: Laptop Asus / Keyboard" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3 outline-none transition" required>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition-all shadow-md shadow-emerald-100 active:scale-95 text-sm">
                            + Simpan Master Produk
                        </button>
                    </form>
                </div>
            </div>

            <!-- FORM 2: INPUT TRANSAKSI -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="bg-indigo-100 text-indigo-600 p-2 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Input Transaksi Penjualan</h2>
                </div>

                <form action="{{ route('sales.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 p-2.5 outline-none font-medium" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Pilih Produk</label>
                            <select name="product_id" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 p-2.5 outline-none font-medium" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($allProducts as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Jenis Pembayaran</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:text-indigo-600">
                                <input type="radio" name="payment_type" value="cash" class="sr-only" required>
                                <span class="text-sm font-semibold">Cash</span>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:text-indigo-600">
                                <input type="radio" name="payment_type" value="kredit" class="sr-only">
                                <span class="text-sm font-semibold">Kredit</span>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:text-indigo-600">
                                <input type="radio" name="payment_type" value="instansi" class="sr-only">
                                <span class="text-sm font-semibold">Instansi</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Jumlah Qty</label>
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="updateQty(-1)" class="w-10 h-10 bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold rounded-xl transition active:scale-95 text-lg flex items-center justify-center">-</button>
                            <input type="number" id="qtyInput" name="qty" value="1" min="1" class="w-20 text-center bg-slate-50 border border-slate-300 p-2 rounded-xl font-bold text-lg text-slate-900" readonly>
                            <button type="button" onclick="updateQty(1)" class="w-10 h-10 bg-indigo-600 text-white hover:bg-indigo-700 font-bold rounded-xl transition active:scale-95 text-lg flex items-center justify-center shadow-md shadow-indigo-100">+</button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition-all shadow-md shadow-indigo-100 active:scale-95 text-sm">
                        Simpan Transaksi
                    </button>
                </form>
            </div>

        </div>

    </main>

    <!-- MODAL EDIT QTY MODERN -->
    <div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full space-y-6 shadow-2xl border border-slate-100">
            <div>
                <h3 class="text-lg font-bold text-slate-900" id="modalProductName">Edit Transaksi Produk</h3>
                <p class="text-xs text-slate-500">Perbarui kuantitas penjualan per jenis pembayaran.</p>
            </div>
            
            <form action="{{ route('sales.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_id" id="modalProductId">
                <input type="hidden" name="transaction_date" value="{{ $date }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tipe Pembayaran</label>
                    <select name="payment_type" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl p-3 outline-none font-medium" required>
                        <option value="cash">Qty Cash</option>
                        <option value="kredit">Qty Kredit</option>
                        <option value="instansi">Qty Instansi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nilai Qty Baru</label>
                    <input type="number" name="qty" min="0" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl p-3 outline-none font-bold" required placeholder="Isi 0 untuk reset nilai">
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                        Simpan Perubahan
                    </button>
                </div>
>>>>>>> f9578cadb6990dc97c7b33d85b1511004d2fdeba
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT LAPORAN -->
    <div id="editReportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-base font-semibold text-slate-900">Edit Laporan</h3>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none transition">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="editReportForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Nama Laporan -->
                <div>
                    <label for="editNameInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nama Laporan</label>
                    <input type="text" id="editNameInput" name="name" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                </div>

                <!-- Tanggal Pembuatan Laporan -->
                <div>
                    <label for="editDateInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Tanggal Pembuatan</label>
                    <input type="date" id="editDateInput" name="report_date" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                </div>

                <!-- Kategori Laporan -->
                <div>
                    <label for="editCategoryInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kategori Laporan</label>
                    <select id="editCategoryInput" name="category" class="w-full border border-slate-300 px-3 py-2 rounded-lg text-sm text-slate-800 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition" required>
                        <option value="cash">Cash</option>
                        <option value="kredit">Kredit</option>
                        <option value="instansi">Instansi</option>
                    </select>
                </div>

                <!-- Jumlah (Qty) dengan stepper bersih -->
                <div>
                    <label for="editQtyInput" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Jumlah (Qty)</label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-white">
                            <button type="button" onclick="adjustQty('editQtyInput', -1)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-base transition border-r border-slate-200 select-none">-</button>
                            <input type="number" id="editQtyInput" name="qty" min="0" class="w-20 text-center py-1.5 text-sm font-semibold text-slate-900 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
                            <button type="button" onclick="adjustQty('editQtyInput', 1)" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-base transition border-l border-slate-200 select-none">+</button>
                        </div>
                        <span class="text-xs text-slate-400">Bisa diketik angka bebas (0 - &infin;)</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2 rounded-lg text-xs font-medium shadow-xs transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Kontrol Interaktif -->
    <script>
<<<<<<< HEAD
        function adjustQty(inputId, delta) {
            const input = document.getElementById(inputId);
            let currentVal = parseInt(input.value);
            if (isNaN(currentVal)) {
                currentVal = 0;
=======
        function updateQty(change) {
            let input = document.getElementById('qtyInput');
            let currentValue = parseInt(input.value) || 1;
            let newValue = currentValue + change;
            if (newValue >= 1) {
                input.value = newValue;
>>>>>>> f9578cadb6990dc97c7b33d85b1511004d2fdeba
            }
            let newVal = currentVal + delta;
            if (newVal < 0) {
                newVal = 0;
            }
            input.value = newVal;
        }

<<<<<<< HEAD
        // Modal Tambah (Create)
        const reportModal = document.getElementById('reportModal');
        const nameInput = document.getElementById('nameInput');

        function openReportModal() {
            reportModal.classList.remove('hidden');
            setTimeout(() => {
                nameInput.focus();
            }, 100);
        }

        function closeReportModal() {
            reportModal.classList.add('hidden');
        }

        reportModal.addEventListener('click', function(e) {
            if (e.target === reportModal) {
                closeReportModal();
            }
        });

        // Modal Edit
        const editReportModal = document.getElementById('editReportModal');
        const editReportForm = document.getElementById('editReportForm');
        const editNameInput = document.getElementById('editNameInput');
        const editDateInput = document.getElementById('editDateInput');
        const editCategoryInput = document.getElementById('editCategoryInput');
        const editQtyInput = document.getElementById('editQtyInput');

        function openEditModal(id, name, date, category, qty) {
            editReportForm.action = '{{ url("/sales") }}/' + id;
            editNameInput.value = name;
            editDateInput.value = date;
            editCategoryInput.value = category;
            editQtyInput.value = (qty !== undefined && qty !== null) ? qty : 1;
            editReportModal.classList.remove('hidden');
            setTimeout(() => {
                editNameInput.focus();
            }, 100);
        }

        function closeEditModal() {
            editReportModal.classList.add('hidden');
        }

        editReportModal.addEventListener('click', function(e) {
            if (e.target === editReportModal) {
                closeEditModal();
            }
        });

        // Global Escape Key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!reportModal.classList.contains('hidden')) {
                    closeReportModal();
                }
                if (!editReportModal.classList.contains('hidden')) {
                    closeEditModal();
                }
            }
        });

        @if($errors->any())
            openReportModal();
        @endif
=======
        function openEditModal(id, name) {
            document.getElementById('modalProductId').value = id;
            document.getElementById('modalProductName').innerText = 'Edit Transaksi: ' + name;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
>>>>>>> f9578cadb6990dc97c7b33d85b1511004d2fdeba
    </script>
</body>
</html>