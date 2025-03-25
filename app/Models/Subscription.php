<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'end_date',
        'is_active',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->user_id = Auth::user()->id;
            $model->end_date = now()->addMonth(30);
        });
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPayments()
    {
        return $this->hasOne(SubscriptionPayment::class);
    }

    /**
     * Get the subscription payments for this subscription.
     */
    public function subscriptionPayment()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }
}
