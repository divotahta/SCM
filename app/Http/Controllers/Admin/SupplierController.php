<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Imports\SuppliersImport;
use App\Exports\SuppliersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query()
            ->when($request->search, function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nama_toko', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('telepon', 'like', "%{$request->search}%");
            })
            ->when($request->jenis, function($q) use ($request) {
                $q->where('jenis', $request->jenis);
            });

        $suppliers = $query->latest()->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'nama_toko' => 'required|string|max:255',
            'jenis' => 'required|string|in:distributor,grosir',
            'nama_bank' => 'nullable|string|max:255',
            'pemegang_rekening' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('foto');
            // dd($data);
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $foto = $request->file('foto');
                $filename = time() . '_' . $foto->getClientOriginalName();
            
                // Simpan secara manual ke folder storage/app/public/suppliers
                $targetPath = storage_path('app/public/suppliers');
            
                // Pastikan folder tujuan ada
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            
                $foto->move($targetPath, $filename);
            
                // Simpan path yang bisa diakses publik
                $data['foto'] = 'suppliers/' . $filename;
            }
            

            Supplier::create($data);

            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Pemasok berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan pemasok: ' . $e->getMessage());
        }
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('purchases.details.product');
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
        'telepon' => 'required|string|max:20',
        'alamat' => 'required|string',
        'nama_toko' => 'required|string|max:255',
        'jenis' => 'required|string|in:distributor,grosir',
        'nama_bank' => 'nullable|string|max:255',
        'pemegang_rekening' => 'nullable|string|max:255',
        'nomor_rekening' => 'nullable|string|max:50',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $data = $request->except('foto');

    if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        // Hapus foto lama jika ada
        if ($supplier->foto && Storage::disk('public')->exists($supplier->foto)) {
            Storage::disk('public')->delete($supplier->foto);
        }

        // Upload file baru
        $foto = $request->file('foto');
        $filename = time() . '_' . $foto->getClientOriginalName();
        $foto->move(storage_path('app/public/suppliers'), $filename);
        $data['foto'] = 'suppliers/' . $filename;
    }

    $supplier->update($data);

    return redirect()->route('admin.suppliers.index')
        ->with('success', 'Data pemasok berhasil diperbarui');
}


    public function destroy(Supplier $supplier)
    {
        // Cek apakah supplier memiliki pembelian
        if ($supplier->purchases()->exists()) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Pemasok tidak dapat dihapus karena memiliki data pembelian');
        }

        if ($supplier->foto) {
            Storage::disk('public')->delete($supplier->foto);
        }
        
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Pemasok berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SuppliersImport, $request->file('file'));

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Data pemasok berhasil diimpor');
    }

    public function export()
    {
        return Excel::download(new SuppliersExport, 'suppliers.xlsx');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'suppliers' => 'required|array'
        ]);

        // Implementasi broadcast message ke pemasok
        // Bisa menggunakan email, SMS, atau WhatsApp

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Pesan berhasil dikirim ke pemasok yang dipilih');
    }
} 