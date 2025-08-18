<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public const STATUS_PENDING  = 'pending'; 
    public const STATUS_PAID     = 'paid';    
    public const STATUS_CANCELED = 'canceled';

    protected $fillable = [ 
        'user_id', 'product_id', 'total_amount', 'address', 'status', 'payme_transaction_id'
    ];

    public function product(): BelongsTo { 
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo { 
        return $this->belongsTo(User::class);
    }
}
