<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = $model->user_id ?? auth()->id();
        });
    }

    /**
     * Relation to Chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relation to Author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
