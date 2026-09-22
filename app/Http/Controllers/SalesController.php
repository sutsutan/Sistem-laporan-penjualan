<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Menampilkan dashboard Laporan Penjualan dengan filter harian dan bulanan.
     */
    public function index(Request $request): View
    {
        $filterDate = $request->input('date');
        $filterMonth = $request->input('month');

        $query = SalesReport::query();

        $activeFilterLabel = null;
        $activeFilterType = null;

        if (! empty($filterDate)) {
            $query->whereDate('report_date', $filterDate);
            $activeFilterType = 'day';
            $activeFilterLabel = Carbon::parse($filterDate)->locale('id')->translatedFormat('d F Y');
        } elseif (! empty($filterMonth)) {
            // $filterMonth diharapkan dalam format YYYY-MM
            $parts = explode('-', $filterMonth);
            if (count($parts) === 2) {
                $year = (int) $parts[0];
                $month = (int) $parts[1];
                $query->whereYear('report_date', $year)
                    ->whereMonth('report_date', $month);
                $activeFilterType = 'month';
                $activeFilterLabel = Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y');
            }
        }

        // Hitung akumulasi berdasarkan filter aktif
        $totalCash = (int) (clone $query)->where('category', 'cash')->sum('qty');
        $totalKredit = (int) (clone $query)->where('category', 'kredit')->sum('qty');
        $totalInstansi = (int) (clone $query)->where('category', 'instansi')->sum('qty');
        $totalAll = $totalCash + $totalKredit + $totalInstansi;

        // Ambil daftar laporan berdasarkan filter
        $recentReports = $query->orderBy('report_date', 'desc')
            ->latest('id')
            ->get();

        // Ambil daftar bulan yang tersedia di database untuk tombol filter cepat
        $availableDates = SalesReport::select('report_date')
            ->distinct()
            ->orderBy('report_date', 'desc')
            ->pluck('report_date');

        $availableMonths = $availableDates->map(function ($date) {
            $carbon = Carbon::parse($date)->locale('id');

            return [
                'value' => $carbon->format('Y-m'),
                'label' => $carbon->translatedFormat('F Y'),
                'month_name' => $carbon->translatedFormat('F'),
                'year' => $carbon->format('Y'),
            ];
        })->unique('value')->values();

        $viewName = view()->exists('sales.laporan1') ? 'sales.laporan1' : 'sales.index';

        return view($viewName, compact(
            'totalCash',
            'totalKredit',
            'totalInstansi',
            'totalAll',
            'recentReports',
            'availableMonths',
            'filterDate',
            'filterMonth',
            'activeFilterLabel',
            'activeFilterType'
        ));
    }

    /**
     * Menyimpan data laporan penjualan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'report_date' => 'required|date',
            'category' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:0',
        ]);

        if (empty($validated['name'])) {
            $categoryLabel = ucfirst($validated['category']);
            $validated['name'] = "Penjualan {$categoryLabel} (".date('d/m/Y', strtotime($validated['report_date'])).')';
        }

        SalesReport::create($validated);

        return redirect()->route('sales.index')->with('success', 'Laporan baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data laporan penjualan.
     */
    public function update(Request $request, SalesReport $salesReport): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'report_date' => 'required|date',
            'category' => 'required|in:cash,kredit,instansi',
            'qty' => 'required|integer|min:0',
        ]);

        if (empty($validated['name'])) {
            $categoryLabel = ucfirst($validated['category']);
            $validated['name'] = "Penjualan {$categoryLabel} (".date('d/m/Y', strtotime($validated['report_date'])).')';
        }

        $salesReport->update($validated);

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * Menghapus data laporan penjualan.
     */
    public function destroy(SalesReport $salesReport): RedirectResponse
    {
        $salesReport->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
}
