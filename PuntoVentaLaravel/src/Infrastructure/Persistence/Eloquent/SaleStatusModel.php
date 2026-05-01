<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class SaleStatusModel extends Model
{
    protected $table      = 'SaleStatuses';
    protected $primaryKey = 'StatusId';
    public    $incrementing = false;
    protected $keyType    = 'int';
    public    $timestamps = false;

    protected $fillable = ['StatusId', 'Name'];
}
