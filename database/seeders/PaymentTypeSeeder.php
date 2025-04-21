<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Maintenance', 'description' => 'General property maintenance and repairs.'],
            ['name' => 'Asset Purchase', 'description' => 'Purchase of new assets for the property.'],
            ['name' => 'Service Charge', 'description' => 'Recurring service charges (e.g., HOA fees, community fees).'],
            ['name' => 'Utilities', 'description' => 'Payment for utilities like water, electricity, gas (if paid by owner).'],
            ['name' => 'Insurance', 'description' => 'Property insurance premiums.'],
            ['name' => 'Management Fee', 'description' => 'Fees paid to property management company.'],
            ['name' => 'Capital Improvement', 'description' => 'Major improvements or renovations that increase property value.'],
            ['name' => 'Legal Fees', 'description' => 'Fees for legal services related to the property.'],
            ['name' => 'Security Deposit Refund', 'description' => 'Refunds of security deposits.'],
            ['name' => 'Other', 'description' => 'Miscellaneous expenses not covered by other categories.'],
        ];

        foreach ($types as $type) {
            PaymentType::firstOrCreate(
                ['slug' => Str::slug($type['name'])],
                $type
            );
        }
    }
}
