<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table      = 'Customers';
    protected $primaryKey = 'CustomerId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = [
        'CustomerId',
        'DocumentNumber',
        'FirstName',
        'LastName',
        'Phone',
        'Email',
        'Address',
        'City',
    ];
}
