<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id1',
        'user_id2',
    ];

    /**
     * Relation to User1
     */
    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id1');
    }

    /**
     * Relation to User2
     */
    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id2');
    }

    /**
     * Get all Message
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
