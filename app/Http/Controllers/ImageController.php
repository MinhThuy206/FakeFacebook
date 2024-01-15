<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Models\Post;
use Carbon\Carbon;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request, $post)
    {
        $post = Post::query()->findOrFail($post);
        $url = Carbon::now() ->timestamp.'.'.$request -> file('image')->extension();
        $request ->file('image') -> storeAs('image', $url,['disk'=> 'public']);
        $post -> images() -> create([
            'url' => '/storage/image/'.$url,
        ]);
        return response() -> json(['message'=>'Image upload success']);
    }

    /**
     * Display the specified resource.
     */
    public function show($image)
    {
        $image = Image::query()->findOrFail($image);
        return response() -> json($image ->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($image, $post)
    {
        $post = Post::query() -> findOrFail($post);
        $image = Image::query() -> findOrFail($image);
        if(auth() -> id() == $post -> first() -> user_id ){
            $image -> delete();
            return response() -> json(['message' =>'Remove success']);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
