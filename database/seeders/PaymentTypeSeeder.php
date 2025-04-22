<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Rent',
            'Security Deposit',
            'Maintenance Fee',
            'Utility Bill',
            'Property Tax',
            'Insurance',
            'Other',
        ];

        foreach ($types as $type) {
            PaymentType::create([
                'name' => $type,
            ]);
        }
    }
}
