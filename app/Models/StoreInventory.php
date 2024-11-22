<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    protected $table = 'store_inventory';

    protected $primaryKey = 'id'; // Set the primary key to 'id'

    public $incrementing = true; // Primary key is an auto-incrementing integer

    protected $keyType = 'int'; // The primary key is an integer

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'SKU',
        'ProductID',
        'Stocks',
        'Consign',
        'SPR',
        'store_id',
        'barcode_numbers', // Add barcode_numbers to the fillable array
    ];

    // Define the relationship to Store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id'); // Adjust 'store_id' if the foreign key is different
    }

    // Cast 'barcode_numbers' as an array (if storing as JSON)
    protected $casts = [
        'barcode_numbers' => 'array', // Automatically cast barcode_numbers to an array
    ];
}
