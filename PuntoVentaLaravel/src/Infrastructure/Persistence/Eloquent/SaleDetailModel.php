<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetailModel extends Model
{
    protected $table      = 'SaleDetails';
    protected $primaryKey = 'SaleDetailId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    /**
     * 'Subtotal' es una columna GENERATED ALWAYS AS STORED en MySQL.
     * No debe incluirse en inserciones; MySQL la calcula automáticamente.
     * Se incluye en $visible para que esté disponible al leer.
     */
    protected $fillable = [
        'SaleDetailId',
        'SaleId',
        'ProductId',
        'Quantity',
        'UnitPrice',
    ];

    protected $casts = [
        'Quantity'  => 'integer',
        'UnitPrice' => 'float',
        'Subtotal'  => 'float',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(SaleModel::class, 'SaleId', 'SaleId');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'ProductId', 'ProductId');
    }
}
