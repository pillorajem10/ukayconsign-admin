<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturnCart extends Model
{
    // The table associated with the model
    protected $table = 'usc_pos_return_cart';

    // The primary key associated with the table
    protected $primaryKey = 'id';

    // Whether the primary key is auto-incrementing (usually true for auto-incremented columns)
    public $incrementing = true;

    // The data type of the primary key (bigint in this case)
    protected $keyType = 'string'; // Change to 'int' if using a regular INT column

    // The attributes that are mass assignable
    protected $fillable = [
        'product_sku',
        'quantity',
        'date_added',
        'product_bundle_id',
        'price',
        'consign',  
        'barcode_numbers',
    ];

    // The attributes that should be mutated to dates
    protected $casts = [
        'date_added' => 'datetime',
        'price' => 'decimal:2',
        'consign' => 'decimal:2',
        'barcode_numbers' => 'array', // Cast it to array if you're saving it as a JSON-like structure
    ];

    // Disables timestamps if your table doesn't have `created_at` and `updated_at` columns
    public $timestamps = false;
}
