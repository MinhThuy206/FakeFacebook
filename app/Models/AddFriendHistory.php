<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Friendships
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int user_id1
 * @property int user_id2
 * @property FriendshipStatus $status
 */
class AddFriendHistory extends Model
{
    use HasFactory;

    protected $table = 'addfriendhistories';
    protected $guarded = ['id','user_id1'];

    protected $casts = [
        'status' => FriendshipStatus::class,
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id1', 'user_id2');
    }

    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'user_id1' => $this->user_id1,
            'user_id2' => $this->user_id2,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        return $array;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model -> user_id1 = auth() -> id();
        });
    }
}
