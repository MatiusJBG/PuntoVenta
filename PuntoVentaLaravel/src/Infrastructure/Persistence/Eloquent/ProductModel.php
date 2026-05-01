<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductModel extends Model
{
    protected $table      = 'Products';
    protected $primaryKey = 'ProductId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'ProductId',
        'Name',
        'Price',
        'Stock',
    ];

    protected $casts = [
        'Price' => 'float',
        'Stock' => 'integer',
    ];

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetailModel::class, 'ProductId', 'ProductId');
    }
}
