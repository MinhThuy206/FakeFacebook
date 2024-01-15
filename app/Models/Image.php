<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int post_id
 * @property string url
 */

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $guarded = ['id'];

    public $timestamps = true;

    public function toArray()
    {
        $array =[
            'id' => $this -> id,
            'url' => $this -> url,
            'created_at' => $this -> created_at,
            'updated_at' => $this -> updated_at,
        ];
        return $array;
    }
}
