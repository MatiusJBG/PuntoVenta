<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleModel extends Model
{
    protected $table      = 'Sales';
    protected $primaryKey = 'SaleId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'SaleId',
        'CustomerId',
        'SaleDate',
        'PaymentMethodId',
        'StatusId',
        'Subtotal',
        'TaxAmount',
        'Total',
    ];

    protected $casts = [
        'SaleDate'  => 'datetime',
        'Subtotal'  => 'float',
        'TaxAmount' => 'float',
        'Total'     => 'float',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'CustomerId', 'CustomerId');
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetailModel::class, 'SaleId', 'SaleId');
    }
}
