<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static create(array $array)
 */
class OrderItem extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];


    protected $fillable = ['order_id', 'product_id', 'amount', 'unit_price'];

    public function product(){
        return $this->belongsTo(products::class);
    }
    public function order(){
        return $this->belongsTo(Orders::class);
    }

}
