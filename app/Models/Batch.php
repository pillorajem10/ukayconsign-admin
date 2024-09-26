<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches'; // Table name
    protected $primaryKey = 'Batch_number'; // Primary key
    public $incrementing = false; // Primary key is not auto-incrementing
    protected $keyType = 'string'; // Primary key type

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'SKU', 
        'Bundle', 
        'ProductID', 
        'Type', 
        'Style', 
        'Color', 
        'Gender', 
        'Category', 
        'Bundle_Qty', 
        'Consign', 
        'SRP', 
        'maxSRP', 
        'PotentialProfit', 
        'Cost', 
        'Stock', 
        'Supplier', 
        'Img_color', 
        'Date', 
        'Bale', 
        'Batch_number', 
        'createdAt' // Include createdAt field
    ];

    protected $dates = [
        'Date', 
        'createdAt' // Handle as date fields if needed
    ];
}
