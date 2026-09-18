<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesReport;
use App\Models\SalesTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    // ==========================================
    // 1. LAPORAN 1 (/sales -> folder sales)
    // ==========================================

    public function index(): View
    {
        // Hitung total agregat berdasarkan akumulasi qty per kategori
        $totalCash = (int) SalesReport::where('category', 'cash')->sum('qty');
        $totalKredit = (int) SalesReport::where('category', 'kredit')->sum('qty');
        $totalInstansi = (int) SalesReport::where('category', 'instansi')->sum('qty');
        $totalAll = $totalCash + $totalKredit + $totalInstansi;

        // Ambil daftar laporan terbaru
        $recentReports = SalesReport::orderBy('report_date', 'desc')
            ->latest('id')
            ->get();

        $viewName = view()->exists('sales.laporan1') ? 'sales.laporan1' : 'sales.index';

        return view($viewName, compact(
            'totalCash',
            'totalKredit',
            'totalInstansi',
            'totalAll',
            'recentReports'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_date' => 'required|date',
            'category' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:0',
        ]);

        SalesReport::create($validated);

        return redirect()->route('sales.index')->with('success', 'Laporan baru berhasil ditambahkan!');
    }

    public function update(Request $request, SalesReport $salesReport): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_date' => 'required|date',
            'category' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:0',
        ]);

        $salesReport->update($validated);

        return redirect()->route('sales.index')->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(SalesReport $salesReport): RedirectResponse
    {
        $salesReport->delete();

        return redirect()->route('sales.index')->with('success', 'Laporan berhasil dihapus!');
    }

    // ==========================================
    // 2. LAPORAN 2 (/tabel -> folder tabel)
    // ==========================================

    public function indexTabel(Request $request): View
    {
        $date = $request->input('date');

        // Daftar tanggal transaksi yang tersedia di database
        $availableDates = SalesTransaction::selectRaw('transaction_date as date, count(*) as total_tx')
            ->groupBy('transaction_date')
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Data rekapitulasi produk berdasarkan tanggal yang dipilih
        $products = collect();
        if ($date) {
            $products = Product::select('products.id', 'products.name')
                ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'cash' THEN sales.qty ELSE 0 END), 0) as total_cash")
                ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'kredit' THEN sales.qty ELSE 0 END), 0) as total_kredit")
                ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'instansi' THEN sales.qty ELSE 0 END), 0) as total_instansi")
                ->leftJoin('sales_transactions as sales', function ($join) use ($date) {
                    $join->on('products.id', '=', 'sales.product_id')
                        ->where('sales.transaction_date', '=', $date);
                })
                ->groupBy('products.id', 'products.name')
                ->get();
        }

        $allProducts = Product::orderBy('name')->get();
        $viewName = view()->exists('tabel.laporan2') ? 'tabel.laporan2' : 'tabel.index';

        return view($viewName, compact('date', 'availableDates', 'products', 'allProducts'));
    }

    public function storeTabelProduct(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Product::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Master produk berhasil ditambahkan!');
    }

    public function storeTabelTransaction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_date' => 'required|date',
            'payment_type' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:1',
        ]);

        SalesTransaction::create($validated);

        return redirect()->route('tabel.index', ['date' => $request->transaction_date])
            ->with('success', 'Transaksi penjualan berhasil disimpan!');
    }

    public function updateTabelTransaction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_date' => 'required|date',
            'payment_type' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:0',
        ]);

        $tx = SalesTransaction::where('product_id', $validated['product_id'])
            ->where('transaction_date', $validated['transaction_date'])
            ->where('payment_type', $validated['payment_type'])
            ->first();

        if ($tx) {
            if ((int) $validated['qty'] === 0) {
                $tx->delete();
            } else {
                $tx->update(['qty' => $validated['qty']]);
            }
        } else {
            if ((int) $validated['qty'] > 0) {
                SalesTransaction::create($validated);
            }
        }

        return redirect()->route('tabel.index', ['date' => $validated['transaction_date']])
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroyTabelProduct(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Product::destroy($request->product_id);

        return redirect()->back()->with('success', 'Master produk dan transaksinya berhasil dihapus!');
    }
}
