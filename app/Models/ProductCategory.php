<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'icon',
    ];
    
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Auth::user()->role === 'user') {
                $model->user_id = Auth::user()->id;
            }
            $model->slug = Str::slug(($model->name));
        });

        self::updating(function ($model) {
            if (Auth::user()->role === 'user') {
                $model->user_id = Auth::user()->id;
            }
            $model->slug = Str::slug(($model->name));
        });


    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
