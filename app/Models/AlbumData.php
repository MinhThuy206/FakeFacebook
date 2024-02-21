<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AlbumData
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int album_id
 * @property int image_id
 */
class AlbumData extends Model
{
    use HasFactory;
    protected $table = 'album_datas';
    public $timestamps = true;
}
