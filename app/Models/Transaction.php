<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'phone_number',
        'table_number',
        'payment_method',
        'total_price',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Auth::user()->role === 'user') {
                $model->user_id = Auth::user()->id;
            }
        });

        self::updating(function ($model) {
            if (Auth::user()->role === 'user') {
                $model->user_id = Auth::user()->id;
            }
        });


    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
