<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Massage
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string name
 * @property int avtGroup_id
 */
class Conservation extends Model
{
    use HasFactory;

    protected $table = 'conservations';

    protected $guarded = ['id'];

    public $timestamps = true;

    /**
     * Relationship with avtGroup (assuming it's the avatar of the group).
     */
    public function avtGroup()
    {
        return $this->belongsTo(Image::class, 'avtGroup_id');
    }

    /**
     * Relationship with users in the conservation.
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserInConservation::class,
            'cons_id', 'id', 'id', 'user_id');
    }

    public function mess()
    {
        return $this->hasMany(MessageInConservation::class, 'cons_id');
    }

    public function toArray()
    {
        $array = [
            'id' => $this->id,
        ];

        if ($this->two){
            $user = $this->users()->where('users.id','!=', auth()->id())->first();
            $user = $user->toArray();
            $array['name'] = $user['name'];
            $array['avatar_url'] = $user['avatar_url'];
        }else{
            if ($this->avatar_id) {
                $avatar = $this->avtGroup()->find($this->avtGroup_id);
                $array['avatar_url'] = $avatar ? $avatar->url : null;
            } else {
                $array['avatar_url'] = null;
            }
            $array['name'] = $this->name;
        }

        if($array['avatar_url'] == null){
            $array['avatar_url'] = "../image/avatar-trang.jpg";
        }

        $last_message = $this->mess()->latest()->first();
        $array['last_message'] = $last_message != null ? $last_message->toArray() : null;

        return $array;
    }
}
