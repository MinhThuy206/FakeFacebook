<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Massage
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int cons_id
 * @property int user_id
 * @property int admin
 */
class UserInConservation extends Model
{
    use HasFactory;

    protected $table = 'user_in_conservations';

    protected $guarded = ['id'];

    public $timestamps = true;

    /**
     * Relationship with the conservation.
     */
    public function conservation()
    {
        return $this->belongsTo(Conservation::class, 'cons_id');
    }

    /**
     * Relationship with the user.
     */
    public function userInConservation()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with the admin (if applicable).
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
