<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laporan Penjualan</title>
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
        function adjustQty(inputId, delta) {
            const input = document.getElementById(inputId);
            let currentVal = parseInt(input.value);
            if (isNaN(currentVal)) {
                currentVal = 0;
            }
            let newVal = currentVal + delta;
            if (newVal < 0) {
                newVal = 0;
            }
            input.value = newVal;
        }

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
    </script>
</body>
</html>