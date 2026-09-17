<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SalesTransaction;

class SalesController extends Controller
{
    public function index()
    {
        // Ambil data produk beserta total akumulasi qty per jenis pembayaran
        $products = Product::select('products.id', 'products.name')
            ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'cash' THEN sales.qty ELSE 0 END), 0) as total_cash")
            ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'kredit' THEN sales.qty ELSE 0 END), 0) as total_kredit")
            ->selectRaw("COALESCE(SUM(CASE WHEN sales.payment_type = 'instansi' THEN sales.qty ELSE 0 END), 0) as total_instansi")
            ->leftJoin('sales_transactions as sales', 'products.id', '=', 'sales.product_id')
            ->groupBy('products.id', 'products.name')
            ->get();

        return view('sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'payment_type' => 'required|in:cash,kredit,instansi',
            'qty'          => 'required|integer|min:1',
        ]);

        // Simpan log transaksi baru
        SalesTransaction::create([
            'product_id'   => $request->product_id,
            'payment_type' => $request->payment_type,
            'qty'          => $request->qty,
        ]);

        return redirect()->back()->with('success', 'Data Qty berhasil ditambahkan!');
    }
}