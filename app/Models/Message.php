<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'user_id', 'conversation_id', 'read'];
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
