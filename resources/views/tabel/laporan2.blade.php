<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laporan Penjualan (Laporan 2)</title>
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
                    <h1 class="text-xl font-bold text-slate-900 leading-tight">Sales Analytics (Laporan 2)</h1>
                    <p class="text-xs text-slate-500">Portal Rekapitulasi Produk & Tanggal - /tabel</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                    &larr; Buka Laporan 1 (/sales)
                </a>
                <div class="text-xs font-semibold px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-200">
                    Portal /tabel Active
                </div>
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

        @if($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm text-sm">
                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FILTER TANGGAL PANEL -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Filter Laporan Penjualan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan tanggal transaksi untuk menampilkan tabel data rekapitulasi.</p>
                </div>
                
                <form action="{{ route('tabel.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <input type="date" name="date" value="{{ $date }}" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 font-medium outline-none transition" required>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-indigo-100 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Tampilkan Tabel
                    </button>
                    
                    @if($date)
                        <a href="{{ route('tabel.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- DAFTAR TANGGAL TERSEDIA -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">🔍 Tanggal Tersedia Yang Memiliki Transaksi:</span>
                <div class="flex flex-wrap gap-2">
                    @forelse($availableDates as $item)
                        <a href="{{ route('tabel.index', ['date' => $item->date]) }}" 
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
                                        <button onclick="openEditModal({{ $p->id }}, '{{ addslashes($p->name) }}')" class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-medium transition shadow-sm active:scale-95">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit Qty
                                        </button>

                                        <!-- Hapus Button -->
                                        <form action="{{ route('tabel.product.destroy') }}" method="POST" onsubmit="return confirm('Yakin menghapus produk {{ addslashes($p->name) }} dari sistem?')" class="inline">
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

                    <form action="{{ route('tabel.product.store') }}" method="POST" class="space-y-4">
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

                <form action="{{ route('tabel.transaction.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" value="{{ $date ?: date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 p-2.5 outline-none font-medium" required>
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
                            <input type="number" id="qtyInput" name="qty" value="1" min="1" class="w-20 text-center bg-slate-50 border border-slate-300 p-2 rounded-xl font-bold text-lg text-slate-900" required>
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
            
            <form action="{{ route('tabel.transaction.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_id" id="modalProductId">
                <input type="hidden" name="transaction_date" value="{{ $date ?: date('Y-m-d') }}">

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
            </form>
        </div>
    </div>

    <script>
        function updateQty(change) {
            let input = document.getElementById('qtyInput');
            let currentValue = parseInt(input.value) || 1;
            let newValue = currentValue + change;
            if (newValue >= 1) {
                input.value = newValue;
            }
        }

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

        // Close on backdrop click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
