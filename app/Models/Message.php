<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Massage
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int userFrom
 * @property int userTo
 * @property string message
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $guarded = ['id', 'userFrom'];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'messages', 'userFrom', 'userTo');
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model -> userFrom = auth() -> id();
        });
    }
}
