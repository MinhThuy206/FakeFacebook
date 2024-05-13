<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'avatar_id',
        'cover_id',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function friends(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(User::class, Friend::class, "user_id1", "id", "id", "user_id2");
    }

    //danh sach nhung dua user1 gui loi ket ban cho
    public function pendingFriend(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(User::class, AddFriendHistory::class, "user_id1", "id", "id", "user_id2");
    }

    // danh sach nguoi gui kb cho user1
    public function listAddFriends()
    {
        return $this->hasManyThrough(User::class, AddFriendHistory::class, "user_id2", "id", "id", "user_id1");
    }

    public function imagesAvt()
    {
        return $this->hasMany(Image::class);
    }

    public function imagesCover()
    {
        return $this->hasMany(Image::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function lastOnline()
    {
        return $this->hasOne(UserStatus::class);
    }

    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
//            'phone' => $this->phone,
//            'email' => $this->email,
            'friends' => $this->friends()->count(),
            'avatar_id' => $this->avatar_id,
            'cover_id' => $this->cover_id,
//            'online' =>0
        ];

        if ($this->avatar_id) {
            $avatar = $this->imagesAvt()->find($this->avatar_id);
            $array['avatar_url'] = $avatar ? $avatar->url : null;
        } else {
            $array['avatar_url'] = null;
        }

        if ($this->cover_id) {
            $cover = $this->imagesCover()->find($this->cover_id);
            $array['cover_url'] = $cover ? $cover->url : null;
        } else {
            $array['cover_url'] = null;
        }
        if (auth()->id() != null && auth()->id() != $this->id) {
            if ($this->friends()->where('id', '=', auth()->id())->exists()) {
                $array['status'] = 'Accepted';
            } else if ($this->pendingFriend()->where('user_id2', '=', auth()->id())->exists()) {
                $array['status'] = 'Pending';
            } else if ($this->listAddFriends()->where('user_id1', '=', auth()->id())->exists()) {
                $array['status'] = 'Sent';
            } else {
                $array['status'] = 'null';
            }
        } else {
            $array['status'] = 'not login';
        }

        if($this->lastOnline()->exists()) {
            $start = Carbon::parse($this->lastOnline->last_online_at);
            $end = Carbon::now();
            if ($start->diffInSeconds($end) < 60) {
                $array['online'] = "online";
            } else {
                $array['online'] = $start->diff($end)->format('%H:%I:%S');
            }
        }else{
            $array['online'] =0;
        }
        return $array;
    }
}
