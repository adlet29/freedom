<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];

    // protected $fillable = [
    //     'subject',
    //     'message',
    //     'file_path',
    //     'user_id',
    //     'viewed'
    // ];

    public function scopeNotViewed($query){
        return $query->where('viewed', 0);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
