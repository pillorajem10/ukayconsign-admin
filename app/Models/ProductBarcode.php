<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBarcode extends Model
{
    use HasFactory;

    protected $table = 'usc_product_barcodes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'product_sku',
        'barcode_image',
        'barcode_number',
        'is_used',
        'received_product_id',
        'batch_number',
    ];

    // Define the relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_sku', 'SKU');
    }

    protected $casts = [
        'is_used' => 'boolean',
    ];
}
