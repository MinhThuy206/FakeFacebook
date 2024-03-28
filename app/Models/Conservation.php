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
    public function users():HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserInConservation::class,
            'cons_id','id','id','user_id');
    }

    public function mess()
    {
        return $this->hasMany(MessageInConservation::class, 'cons_id');
    }
}
