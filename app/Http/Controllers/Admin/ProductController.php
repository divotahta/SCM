<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->when($request->search, function ($q) use ($request) {
                return $q->where('nama_produk', 'like', "%{$request->search}%")
                    ->orWhere('kode_produk', 'like', "%{$request->search}%");
            })
            ->when($request->kategori_id, function ($q) use ($request) {
                return $q->where('kategori_id', $request->kategori_id);
            })
            ->when($request->status_stok, function ($q) use ($request) {
                if ($request->status_stok === 'low') {
                    return $q->where('stok', '<=', 10);
                } elseif ($request->status_stok === 'out') {
                    return $q->where('stok', 0);
                }
                return $q;
            });

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('admin.products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|string|max:50|unique:products',
            'kategori_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar_produk')) {
            $image = $request->file('gambar_produk');
            $imageName = Str::slug($request->nama_produk) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $data['gambar_produk'] = $imageName;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('Admin.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|string|max:50|unique:products,kode_produk,' . $product->id,
            'kategori_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('gambar_produk');

        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($product->gambar_produk) {
                Storage::delete('public/products/' . $product->gambar_produk);
            }

            // Upload gambar baru
            $image = $request->file('gambar_produk');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $data['gambar_produk'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }



    public function exportExcel(Request $request)
    {
        return Excel::download(new ProductsExport($request), 'products.xlsx');
    }

    public function checkCode(Request $request)
    {
        $code = $request->code;
        $productId = $request->produk_id;

        $query = Product::where('kode_produk', $code);

        if ($productId) {
            $query->where('id', '!=', $productId);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'stockHistories' => function ($query) {
            $query->latest()->take(10);
        }]);

        // Hitung total penjualan
        $totalSold = DB::table('transaction_detail')
            ->where('produk_id', $product->id)
            ->sum('jumlah');

        // Hitung total pembelian
        $totalPurchased = DB::table('purchase_details')
            ->where('produk_id', $product->id)
            ->sum('jumlah');

        // Ambil data penjualan 6 bulan terakhir untuk grafik
        $monthlySales = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.transaksi_id', '=', 'transaction.id') // ganti transaction_id -> transaksi_id
            ->where('transaction_detail.produk_id', $product->id)
            ->where('transaction.created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(transaction.created_at) as bulan'),
                DB::raw('YEAR(transaction.created_at) as tahun'),
                DB::raw('SUM(transaction_detail.jumlah) as total_terjual')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();


        return view('Admin.products.show', compact(
            'product',
            'totalSold',
            'totalPurchased',
            'monthlySales'
        ));
    }
}
