<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=['body','user_id','product_id'];
    protected $with=['author'];

    public function product()
   {
        return $this->belongsTo(Product::class);
   }

     public function author()
   {
        return $this->belongsTo(User::class,'user_id');
   }
}
