<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function friends(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this -> hasManyThrough(User::class,Friend::class, "user_id1", "id", "id", "user_id2");
    }

    public function pendingFriend(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this -> hasManyThrough(User::class,AddFriendHistory::class, "user_id1", "id", "id", "user_id2");
    }

    public function toArray(){
        $array =[
            'id' => $this -> id,
            'name' => $this -> name,
            'phone' => $this -> phone,
            'email' => $this -> email,
            'friends' =>  $this -> friends() -> count()
        ];
        return $array;
    }



}
