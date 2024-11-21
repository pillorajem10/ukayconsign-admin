<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = [
        'store_name',
        'store_owner',   // This refers to the User ID (Foreign Key)
        'store_address',
        'store_phone_number',
        'store_email',
        'store_total_earnings',
        'store_status', 
        'store_fb_link', 
    ];    

    /**
     * Get the user that owns the store.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'store_owner'); 
    }
}
