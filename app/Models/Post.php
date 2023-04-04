<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $primaryKey = 'post_id';
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = ['title', 'content', 'category'];

    // manejo de categorías como array
    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = json_encode($value);
    }
    public function getCategoryAttribute($value)
    {
        return $this->attributes['category'] = json_decode($value);
    }

    // muchos a muchos
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_post');
    }
}
