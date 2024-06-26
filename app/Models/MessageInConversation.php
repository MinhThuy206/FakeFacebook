<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough as HasManyThroughAlias;

/**
 * App\Models\Massage
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int cons_id
 * @property int userFrom
 * @property string message
 */
class MessageInConversation extends Model
{
    use HasFactory;

    protected $table = 'message_in_conversations';

    protected $guarded = ['id'];

    public $timestamps = true;


//    /**
//     * Relationship with users in the conservation.
//     */
//    public function user(): belongsToMany
//    {
//        return $this->belongsToMany(User::class, 'message_in_conservations', 'userFrom');
//    }

    public function toArray()
    {
        $user = User::query()->where('id','=',$this->userFrom)->firstOrFail()->toArray();
       $array = [
           'conservationId' => $this->cons_id,
           'created_at' =>$this->created_at,
           'userFrom' => [
               'id' => $user['id'],
               'name' => $user['name'],
               'avatar_url' => $user['avatar_url']
           ],
           'message' => $this->message
       ];

        if(empty($user['avatar_url'])) {
            $array['userFrom']['avatar_url'] = "image/avatar-trang.jpg";
        }

        return $array;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model -> userFrom = auth() -> id();
        });
    }
}
