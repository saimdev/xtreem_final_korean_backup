<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    // use SoftDeletes;
    protected $table = 'transactions';
    protected $primaryKey = 'no';

    protected $fillable = [
        'userno',
        'userid',
        'tid',
        'type',
        'amount',
        'before',
        'status',
        'gameid',
        'gametype',
        'gameround',
        'gametitle',
        'gamevendor',
        'detail',
        'detailUpdate',
        'created_at',
        'updated_at',
        'deleted_at',
        'processed_at',
    ];

    

}

