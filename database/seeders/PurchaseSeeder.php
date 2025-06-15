<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        // Ambil data yang diperlukan
        $suppliers = Supplier::all();
        $users = User::all();
        $admin = $users->first(); // Admin untuk approval
        $owner = $users->where('role', 'owner')->first(); // Owner untuk approval

        if (!$suppliers->count() || !$users->count()) {
            $this->command->error('Data supplier atau user belum tersedia!');
            return;
        }

        // Status yang akan digunakan
        $statuses = ['pending', 'approved', 'rejected', 'received'];
        $reasons = [
            'Stok tidak sesuai dengan permintaan',
            'Harga terlalu tinggi',
            'Kualitas barang tidak sesuai',
            'Barang tidak tersedia',
            'Pembatalan dari supplier'
        ];

        // Buat 20 pembelian
        for ($i = 1; $i <= 20; $i++) {
            DB::beginTransaction();
            try {
                // Buat pembelian
                $purchase = Purchase::create([
                    'tanggal_pembelian' => now()->subDays(rand(1, 30)),
                    'nomor_pembelian' => 'PUR' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'pemasok_id' => $suppliers->random()->id,
                    'total_amount' => 0,
                    'catatan' => 'Catatan untuk pembelian #' . $i,
                    'status_pembelian' => $status = $statuses[array_rand($statuses)],
                    'dibuat_oleh' => $users->random()->id,
                ]);

                // Set status dan user yang terkait
                switch ($status) {
                    case 'approved':
                        $purchase->update([
                            'disetujui_oleh' => $owner->id,
                            'disetujui_pada' => $purchase->created_at->addHours(rand(1, 24))
                        ]);
                        break;
                    case 'rejected':
                        $purchase->update([
                            'ditolak_oleh' => $owner->id,
                            'ditolak_pada' => $purchase->created_at->addHours(rand(1, 24)),
                            'alasan_penolakan' => $reasons[array_rand($reasons)]
                        ]);
                        break;
                    case 'received':
                        $purchase->update([
                            'disetujui_oleh' => $owner->id,
                            'disetujui_pada' => $purchase->created_at->addHours(rand(1, 24)),
                            'diterima_oleh' => $admin->id,
                            'diterima_pada' => $purchase->created_at->addHours(rand(25, 48))
                        ]);
                        break;
                }

                // Buat detail pembelian (1-5 item per pembelian)
                $total = 0;
                $itemCount = rand(1, 5);
                
                for ($j = 0; $j < $itemCount; $j++) {
                    $quantity = rand(5, 50);
                    $price = rand(50000, 500000);
                    $subtotal = $quantity * $price;
                    $total += $subtotal;

                    PurchaseDetail::create([
                        'pembelian_id' => $purchase->id,
                        'produk_id' => rand(1, 10), // Asumsi ada 10 produk
                        'jumlah' => $quantity,
                        'harga_satuan' => $price,
                        'total' => $subtotal,
                        'catatan' => 'Catatan untuk item #' . ($j + 1)
                    ]);
                }

                // Update total pembelian
                $purchase->update(['total_amount' => $total]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Error creating purchase #' . $i . ': ' . $e->getMessage());
            }
        }

        $this->command->info('Purchase seeder completed successfully!');
    }
} 