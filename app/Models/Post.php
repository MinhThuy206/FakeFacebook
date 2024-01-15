<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 * @property int id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int user_id
 * @property string content
 * @property int like
 * @property int comment_count
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $guarded = ['id','user_id', 'like', 'comment_count']; // khong cho nguoi dung nhap id, like, comment

    public $timestamps = true;

    public function user(){
        return $this ->belongsTo(User::class);
    }

    public function images(){
        return $this ->hasMany(Image::class);
    }

    public  function toArray ()
    {
        $array =[
            'id' => $this -> id,
            'user_id' => $this -> user_id,
            'content' => $this -> content,
            'images' => array(),
            'like' => $this -> like,
            'comment' => $this -> comment_count,
            'created_at' => $this -> created_at,
            'updated_at' => $this -> updated_at,
            'user' => $this -> user() -> first() -> name
        ];
        $images = $this->images() ->get();
        foreach ($images as $image){
            $array['images'][]= $image -> toArray();
        }
        return $array;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model -> user_id = auth() -> id();
        });
    }


}
