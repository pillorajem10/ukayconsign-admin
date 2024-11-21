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
        'user',
        'prod_design',
        'prod_brand',
    ];

    // The attributes that should be mutated to dates
    protected $dates = ['date_added']; // Laravel will automatically cast this as a datetime

    // Disables timestamps if your table doesn't have `created_at` and `updated_at` columns
    public $timestamps = false;
}
