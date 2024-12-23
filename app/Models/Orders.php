<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method static create(array $array)
 */
class Orders extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];

    protected $fillable = ['user_id', 'total_invoice'];

    public function user() :belongsTo{
        return $this->belongsTo(User::class , 'user_id');
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


}
