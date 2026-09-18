<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laporan Penjualan</title>
    <!-- CSS Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto space-y-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- 1. TABEL REKAPITULASI AKUMULASI QTY -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Akumulasi Penjualan Produk</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 border-b">
                            <th class="p-3 border">Nama Produk</th>
                            <th class="p-3 border text-center">Qty Cash</th>
                            <th class="p-3 border text-center">Qty Kredit</th>
                            <th class="p-3 border text-center">Qty Instansi</th>
                            <th class="p-3 border text-center bg-gray-200 font-bold">Total Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-3 border font-medium">{{ $p->name }}</td>
                            <td class="p-3 border text-center text-green-600 font-semibold">{{ $p->total_cash }}</td>
                            <td class="p-3 border text-center text-yellow-600 font-semibold">{{ $p->total_kredit }}</td>
                            <td class="p-3 border text-center text-blue-600 font-semibold">{{ $p->total_instansi }}</td>
                            <td class="p-3 border text-center font-bold bg-gray-50">{{ $p->total_cash + $p->total_kredit + $p->total_instansi }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data produk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. FORM INPUT TRANSAKSI -->
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Input Penjualan</h2>
            <form action="{{ route('sales.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Pilih Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                    <select name="product_id" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran</label>
                    <div class="flex gap-6 mt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="payment_type" value="cash" class="text-blue-600 focus:ring-blue-500" required>
                            <span class="ml-2 text-gray-700">Cash</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="payment_type" value="kredit" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Kredit</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="payment_type" value="instansi" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Instansi</span>
                        </label>
                    </div>
                </div>

                <!-- Tombol +/- Qty -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Qty)</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="updateQty(-1)" class="w-10 h-10 bg-red-500 text-white text-xl font-bold rounded-md hover:bg-red-600 active:scale-95 transition">-</button>
                        <input type="number" id="qtyInput" name="qty" value="1" min="1" class="w-20 text-center border border-gray-300 p-2 rounded-md font-semibold text-lg" readonly>
                        <button type="button" onclick="updateQty(1)" class="w-10 h-10 bg-green-500 text-white text-xl font-bold rounded-md hover:bg-green-600 active:scale-95 transition">+</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-md font-semibold hover:bg-blue-700 active:scale-98 transition">
                    Submit Transaksi
                </button>
            </form>
        </div>

    </div>

    <script>
        function updateQty(change) {
            let input = document.getElementById('qtyInput');
            let currentValue = parseInt(input.value) || 1;
            let newValue = currentValue + change;
            
            // Batas minimal 1 item
            if (newValue >= 1) {
                input.value = newValue;
            }
        }
    </script>
</body>
</html>