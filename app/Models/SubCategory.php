<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = ['category_id', 'name', 'slug', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sub) {
            $sub->slug = Str::slug($sub->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }
}