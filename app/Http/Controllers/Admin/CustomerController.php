<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('telepon', 'like', "%{$request->search}%");
            });
        }

        // Filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Sort
        $sort = $request->get('sort', 'nama');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $customers = $query->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jenis' => 'nullable|string|in:perorangan,perusahaan',
            'nama_bank' => 'nullable|string|max:255',
            'pemegang_rekening' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/customers', $filename);
            $data['foto'] = $filename;
        }

        $customer = Customer::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Customer $customer)
    {
        $orders = $customer->orders()
            ->with(['details.product'])
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'orders'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis' => 'required|string|in:perorangan,perusahaan',
            'nama_bank' => 'nullable|string|max:255',
            'pemegang_rekening' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($customer->foto) {
                Storage::delete('public/customers/' . $customer->foto);
            }

            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/customers', $filename);
            $data['foto'] = $filename;
        }

        $customer->update($data);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orders()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki pesanan');
        }

        // Hapus foto jika ada
        if ($customer->foto) {
            Storage::delete('public/customers/' . $customer->foto);
        }

        $customer->delete();
        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        Excel::import(new CustomersImport, $request->file('file'));

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diimpor');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        if ($format == 'pdf') {
            $customers = Customer::all();
            $pdf = PDF::loadView('admin.customers.export-pdf', compact('customers'));
            return $pdf->download('daftar-pelanggan.pdf');
        }

        return Excel::download(new CustomersExport, 'daftar-pelanggan.xlsx');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'customers' => 'required|array',
            'customers.*' => 'exists:customers,id'
        ]);

        $customers = Customer::whereIn('id', $request->customers)
            ->whereNotNull('phone')
            ->get();

        foreach ($customers as $customer) {
            // Implementasi integrasi WhatsApp
            // Contoh menggunakan API WhatsApp Business
            $this->sendWhatsAppMessage($customer->phone, $request->message);
        }

        return back()->with('success', 'Pesan berhasil dikirim ke ' . count($customers) . ' pelanggan');
    }

    protected function sendWhatsAppMessage($phone, $message)
    {
        // Implementasi pengiriman pesan WhatsApp
        // Contoh menggunakan API WhatsApp Business
        // $client = new WhatsAppClient(config('services.whatsapp.token'));
        // $client->sendMessage($phone, $message);
    }
} 