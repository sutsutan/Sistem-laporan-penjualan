<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
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

        return view('sales.index', compact(
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

        // Simpan entri laporan baru
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
}
