<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // Halaman /sales (View dari folder 'sales.index')
    public function index(Request $request)
    {
        $date = $request->input('date');
        $products = collect();

        if ($date) {
            $products = Product::withSum(['salesTransactions as total_cash' => function ($query) use ($date) {
                $query->where('payment_type', 'cash')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->withSum(['salesTransactions as total_kredit' => function ($query) use ($date) {
                $query->where('payment_type', 'kredit')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->withSum(['salesTransactions as total_instansi' => function ($query) use ($date) {
                $query->where('payment_type', 'instansi')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->get()
            ->map(function ($product) {
                $product->total_cash = $product->total_cash ?? 0;
                $product->total_kredit = $product->total_kredit ?? 0;
                $product->total_instansi = $product->total_instansi ?? 0;
                return $product;
            });
        }

        $allProducts = Product::all();

        $availableDates = SalesTransaction::select(DB::raw('DATE(transaction_date) as date'), DB::raw('COUNT(*) as total_tx'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('sales.index', compact('products', 'allProducts', 'date', 'availableDates'));
    }

    // Halaman /tabel (View dari folder 'tabel.index')
    public function indexTabel(Request $request)
    {
        $date = $request->input('date');
        $products = collect();

        if ($date) {
            $products = Product::withSum(['salesTransactions as total_cash' => function ($query) use ($date) {
                $query->where('payment_type', 'cash')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->withSum(['salesTransactions as total_kredit' => function ($query) use ($date) {
                $query->where('payment_type', 'kredit')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->withSum(['salesTransactions as total_instansi' => function ($query) use ($date) {
                $query->where('payment_type', 'instansi')
                      ->whereDate('transaction_date', $date);
            }], 'qty')
            ->get()
            ->map(function ($product) {
                $product->total_cash = $product->total_cash ?? 0;
                $product->total_kredit = $product->total_kredit ?? 0;
                $product->total_instansi = $product->total_instansi ?? 0;
                return $product;
            });
        }

        $allProducts = Product::all();

        $availableDates = SalesTransaction::select(DB::raw('DATE(transaction_date) as date'), DB::raw('COUNT(*) as total_tx'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('tabel.index', compact('products', 'allProducts', 'date', 'availableDates'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Product::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'payment_type'     => 'required|in:cash,kredit,instansi',
            'qty'              => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        SalesTransaction::create([
            'product_id'       => $request->product_id,
            'payment_type'     => $request->payment_type,
            'qty'              => $request->qty,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'transaction_date' => 'required|date',
            'payment_type'     => 'required|in:cash,kredit,instansi',
            'qty'              => 'required|integer|min:0',
        ]);

        $transaction = SalesTransaction::where('product_id', $request->product_id)
            ->whereDate('transaction_date', $request->transaction_date)
            ->where('payment_type', $request->payment_type)
            ->first();

        if ($transaction) {
            if ($request->qty == 0) {
                $transaction->delete();
            } else {
                $transaction->update(['qty' => $request->qty]);
            }
        } else if ($request->qty > 0) {
            SalesTransaction::create([
                'product_id'       => $request->product_id,
                'payment_type'     => $request->payment_type,
                'qty'              => $request->qty,
                'transaction_date' => $request->transaction_date,
            ]);
        }

        return redirect()->back()->with('success', 'Data transaksi berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $productName = $product->name;
        $product->delete();

        return redirect()->back()->with('success', "Produk '{$productName}' beserta seluruh transaksinya berhasil dihapus!");
    }
}