<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ApiTrait;

class Post extends Model
{
    use HasFactory, ApiTrait;

    protected $fillable = ['name', 'slug', 'extract', 'content', 'status', 'category_id', 'user_id'];

    const BORRADOR = 1;
    const PUBLICADO = 2;

    //Ralación uno a muchos inversa
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Relación de muchos a muchos
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    //Relacion uno a muchos polimorfica
    public function images(){
        return $this->morphToMany(Image::class, 'imageable');
    }
}
