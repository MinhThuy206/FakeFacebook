<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AlbumMeta
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string name
 * @property int user_id
 */
class AlbumMeta extends Model
{
    use HasFactory;

    protected $table = 'album_metas';
    protected $guarded = ['id'];
    public $timestamps = true;
}
