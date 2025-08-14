# Property Sale Management Guide

This comprehensive guide outlines the process of handling property sales within the PropertyLivewire system, ensuring all transaction records are preserved and properties are properly archived.

## Overview

When a property is sold, the system must:
1. Preserve all historical transaction records
2. Archive the property with a new status
3. Maintain data integrity for reporting purposes
4. Ensure proper audit trails

## Current System Structure

### Property Status Values
The system currently supports these property statuses:
- `VACANT` - Property is available for rent
- `LEASED` - Property is currently rented
- `MAINTENANCE` - Property is under maintenance

### Related Models
- **Property**: Core property information with status tracking
- **Contract**: Rental agreements linked to properties
- **Receipt**: Financial transactions for each contract
- **Owner**: Property ownership information

## Implementation Steps

### Step 1: Database Schema Updates

#### 1.1 Add New Property Status
Add `SOLD` status to the existing property status options:

```php
// In app/Livewire/Properties/Create.php and Edit.php
'propertyStatuses' => ['VACANT', 'LEASED', 'MAINTENANCE', 'SOLD']
```

#### 1.2 Add Sale Information Fields
Create a migration to add sale-related fields to the properties table:

```php
// Migration: add_sale_fields_to_properties_table.php
Schema::table('properties', function (Blueprint $table) {
    $table->date('sale_date')->nullable()->after('status');
    $table->decimal('sale_price', 15, 2)->nullable()->after('sale_date');
    $table->string('buyer_name')->nullable()->after('sale_price');
    $table->text('sale_notes')->nullable()->after('buyer_name');
    $table->boolean('is_archived')->default(false)->after('is_visible');
    $table->timestamp('archived_at')->nullable()->after('is_archived');
    $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
    
    $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
});
```

### Step 2: Model Updates

#### 2.1 Update Property Model
Add sale-related fields and relationships:

```php
// In app/Models/Property.php
protected $fillable = [
    // ... existing fields
    'sale_date',
    'sale_price', 
    'buyer_name',
    'sale_notes',
    'is_archived',
    'archived_at',
    'archived_by'
];

protected $casts = [
    // ... existing casts
    'sale_date' => 'date',
    'sale_price' => 'decimal:2',
    'is_archived' => 'boolean',
    'archived_at' => 'datetime'
];

// Relationship to user who archived the property
public function archivedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'archived_by');
}

// Scope for active (non-archived) properties
public function scopeActive($query)
{
    return $query->where('is_archived', false);
}

// Scope for archived properties
public function scopeArchived($query)
{
    return $query->where('is_archived', true);
}
```

### Step 3: Property Sale Process

#### 3.1 Create Property Sale Component
Create a dedicated Livewire component for handling property sales:

```php
// app/Livewire/Properties/Sale.php
class Sale extends Component
{
    public Property $property;
    public $sale_date;
    public $sale_price;
    public $buyer_name;
    public $sale_notes;
    public $confirm_sale = false;
    
    protected $rules = [
        'sale_date' => 'required|date|before_or_equal:today',
        'sale_price' => 'required|numeric|min:0',
        'buyer_name' => 'required|string|max:255',
        'sale_notes' => 'nullable|string|max:1000'
    ];
    
    public function mount(Property $property)
    {
        $this->property = $property;
        $this->sale_date = now()->format('Y-m-d');
    }
    
    public function processSale()
    {
        $this->validate();
        
        if (!$this->confirm_sale) {
            $this->addError('confirm_sale', 'Please confirm the sale before proceeding.');
            return;
        }
        
        DB::beginTransaction();
        
        try {
            // 1. Close any active contracts
            $this->closeActiveContracts();
            
            // 2. Update property with sale information
            $this->property->update([
                'status' => 'SOLD',
                'sale_date' => $this->sale_date,
                'sale_price' => $this->sale_price,
                'buyer_name' => $this->buyer_name,
                'sale_notes' => $this->sale_notes,
                'is_archived' => true,
                'archived_at' => now(),
                'archived_by' => auth()->id()
            ]);
            
            // 3. Log the sale activity
            $this->logSaleActivity();
            
            DB::commit();
            
            session()->flash('message', 'Property sale processed successfully!');
            session()->flash('alert-type', 'success');
            
            return redirect()->route('properties.table');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Property sale error: ' . $e->getMessage());
            session()->flash('message', 'Error processing sale: ' . $e->getMessage());
            session()->flash('alert-type', 'error');
        }
    }
    
    private function closeActiveContracts()
    {
        $activeContracts = $this->property->contracts()
            ->where('validity', 'YES')
            ->get();
            
        foreach ($activeContracts as $contract) {
            $contract->update([
                'validity' => 'NO',
                'type' => 'Terminated - Property Sold',
                'close_date' => $this->sale_date,
                'termination_reason' => 'Property sold to ' . $this->buyer_name
            ]);
        }
    }
    
    private function logSaleActivity()
    {
        // Create activity log entry
        activity()
            ->performedOn($this->property)
            ->causedBy(auth()->user())
            ->withProperties([
                'sale_price' => $this->sale_price,
                'buyer_name' => $this->buyer_name,
                'sale_date' => $this->sale_date
            ])
            ->log('Property sold');
    }
}
```

#### 3.2 Create Sale View
Create the corresponding Blade template:

```blade
{{-- resources/views/livewire/properties/sale.blade.php --}}
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Process Property Sale: {{ $property->name }}
            </h3>
            
            {{-- Property Information Summary --}}
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Property Details</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="font-medium">Type:</span> {{ $property->type }}</div>
                    <div><span class="font-medium">Current Status:</span> {{ $property->status }}</div>
                    <div><span class="font-medium">Purchase Value:</span> ${{ number_format($property->purchase_value) }}</div>
                    <div><span class="font-medium">Owner:</span> {{ $property->owner->name }}</div>
                </div>
            </div>
            
            {{-- Active Contracts Warning --}}
            @if($property->contracts()->where('validity', 'YES')->exists())
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <flux:icon name="exclamation-triangle" class="h-5 w-5 text-yellow-400" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Active Contracts</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            This property has active contracts that will be automatically terminated upon sale.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            <form wire:submit="processSale">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- Sale Date --}}
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sale Date *
                        </label>
                        <input type="date" wire:model="sale_date" id="sale_date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        @error('sale_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Sale Price --}}
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sale Price *
                        </label>
                        <input type="number" step="0.01" wire:model="sale_price" id="sale_price" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        @error('sale_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Buyer Name --}}
                    <div class="sm:col-span-2">
                        <label for="buyer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Buyer Name *
                        </label>
                        <input type="text" wire:model="buyer_name" id="buyer_name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        @error('buyer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Sale Notes --}}
                    <div class="sm:col-span-2">
                        <label for="sale_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Sale Notes
                        </label>
                        <textarea wire:model="sale_notes" id="sale_notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                  placeholder="Additional notes about the sale..."></textarea>
                        @error('sale_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Confirmation Checkbox --}}
                    <div class="sm:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="confirm_sale" id="confirm_sale" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="confirm_sale" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                I confirm that this property has been sold and understand that all active contracts will be terminated.
                            </label>
                        </div>
                        @error('confirm_sale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('properties.table') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-red-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Process Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### Step 4: Update Property Management Interface

#### 4.1 Update Property Table
Modify the properties table to show sold properties and add sale action:

```php
// In app/Livewire/Properties/Table.php
public $showArchived = false;

public function toggleArchived()
{
    $this->showArchived = !$this->showArchived;
    $this->resetPage();
}

public function render()
{
    $query = Property::with(['owner', 'contracts'])
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('community', 'like', '%' . $this->search . '%');
        });
    
    if ($this->showArchived) {
        $query->archived();
    } else {
        $query->active();
    }
    
    $properties = $query->paginate(10);
    
    return view('livewire.properties.table', compact('properties'));
}
```

#### 4.2 Update Property Table View
Add archive toggle and sale action button:

```blade
{{-- In resources/views/livewire/properties/table.blade.php --}}
{{-- Add toggle button for archived properties --}}
<div class="mb-4">
    <button wire:click="toggleArchived" 
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        @if($showArchived)
            Show Active Properties
        @else
            Show Archived Properties
        @endif
    </button>
</div>

{{-- Update status display to include SOLD --}}
@if($property->status === 'SOLD')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
        Sold
    </span>
@endif

{{-- Add sale action button --}}
@if($property->status !== 'SOLD' && (auth()->user()->hasRole('Super Admin') || auth()->user()->can('sell properties')))
    <a href="{{ route('properties.sale', $property) }}" 
       class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400"
       title="Process Sale">
        <flux:icon name="currency-dollar" class="h-5 w-5" />
        <span class="sr-only">Process Sale</span>
    </a>
@endif
```

### Step 5: Reporting and Analytics

#### 5.1 Update Dashboard Metrics
Modify dashboard components to exclude sold properties from active metrics:

```php
// In app/Livewire/Dashboard/PropertyOverview.php
public function render()
{
    $totalProperties = Property::active()->count();
    $occupiedProperties = Property::active()->where('status', 'LEASED')->count();
    $vacantProperties = Property::active()->where('status', 'VACANT')->count();
    $maintenanceProperties = Property::active()->where('status', 'MAINTENANCE')->count();
    
    // Add sold properties metrics
    $soldProperties = Property::where('status', 'SOLD')->count();
    $totalSaleValue = Property::where('status', 'SOLD')->sum('sale_price');
    
    // ... rest of the component
}
```

#### 5.2 Create Property Sale Report
Create a dedicated report for property sales:

```php
// app/Livewire/Reports/PropertySaleReport.php
class PropertySaleReport extends Component
{
    public $dateFrom;
    public $dateTo;
    
    public function mount()
    {
        $this->dateFrom = now()->startOfYear()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }
    
    public function render()
    {
        $soldProperties = Property::where('status', 'SOLD')
            ->when($this->dateFrom, function ($query) {
                $query->where('sale_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->where('sale_date', '<=', $this->dateTo);
            })
            ->with(['owner', 'archivedBy'])
            ->orderBy('sale_date', 'desc')
            ->get();
            
        $totalSaleValue = $soldProperties->sum('sale_price');
        $averageSalePrice = $soldProperties->avg('sale_price');
        
        return view('livewire.reports.property-sale-report', [
            'soldProperties' => $soldProperties,
            'totalSaleValue' => $totalSaleValue,
            'averageSalePrice' => $averageSalePrice
        ]);
    }
}
```

### Step 6: Data Preservation and Audit Trail

#### 6.1 Transaction Record Preservation
All existing transaction records (receipts, contracts) are automatically preserved through foreign key relationships. The system maintains:

- **Complete Receipt History**: All payment records remain accessible
- **Contract Archives**: Terminated contracts retain all historical data
- **Audit Logs**: Activity logging tracks all sale transactions
- **Media Files**: Property documents and receipts remain stored

#### 6.2 Reporting Access
Archived properties and their transaction history remain accessible through:

- **Tenant Ledger Reports**: Include historical data for sold properties
- **Contract Reports**: Show terminated contracts with sale reasons
- **Financial Reports**: Include all historical transactions
- **Property Sale Reports**: Dedicated reporting for sale analytics

### Step 7: Security and Permissions

#### 7.1 Permission Setup
Add new permission for property sales:

```php
// In database/seeders/PermissionSeeder.php
Permission::create(['name' => 'sell properties']);
```

#### 7.2 Route Protection
Protect sale routes with appropriate middleware:

```php
// In routes/web.php
Route::middleware(['auth', 'can:sell properties'])->group(function () {
    Route::get('/properties/{property}/sale', [PropertySaleController::class, 'show'])
        ->name('properties.sale');
});
```

## Best Practices

### 1. Data Integrity
- Always use database transactions when processing sales
- Validate all input data before processing
- Maintain referential integrity through proper foreign keys

### 2. Audit Trail
- Log all sale activities with timestamps and user information
- Preserve original data before any modifications
- Maintain detailed notes for each sale transaction

### 3. User Experience
- Provide clear warnings about contract terminations
- Show comprehensive property information before sale
- Implement confirmation steps for irreversible actions

### 4. Reporting
- Ensure sold properties don't skew active property metrics
- Maintain historical reporting capabilities
- Provide dedicated sale analytics and reports

## Migration Checklist

- [ ] Create database migration for sale fields
- [ ] Update Property model with new fields and relationships
- [ ] Create Property Sale Livewire component
- [ ] Update property table to show/hide archived properties
- [ ] Add sale action buttons with proper permissions
- [ ] Update dashboard metrics to exclude sold properties
- [ ] Create property sale reports
- [ ] Test data preservation and audit trails
- [ ] Update user permissions and roles
- [ ] Document the new sale process for users

## Conclusion

This implementation provides a comprehensive solution for handling property sales while maintaining complete data integrity and audit trails. The system ensures that all historical transaction records are preserved, properties are properly archived, and reporting capabilities remain intact for both active and sold properties.

The modular approach allows for easy maintenance and future enhancements while following Laravel and Livewire best practices.