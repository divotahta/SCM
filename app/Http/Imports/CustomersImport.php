<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Customer([
            'code' => 'CUST-' . Str::random(8),
            'name' => $row['name'],
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
            'notes' => $row['notes'] ?? null,
            'points' => 0,
            'loyalty_level' => 'bronze',
            'total_purchase' => 0
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ];
    }
} 