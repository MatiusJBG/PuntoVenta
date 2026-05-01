<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodModel extends Model
{
    protected $table      = 'PaymentMethods';
    protected $primaryKey = 'PaymentMethodId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = ['PaymentMethodId', 'Name'];
}
