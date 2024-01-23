<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateImageRequest;
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
    public function store(StoreImageRequest $request)
    {
        $url = Carbon::now() ->timestamp.'.'.$request -> file('image')->extension();
//        $request ->file('image') -> storeAs('image', $url,['disk'=> 'public']);
        $request->file('image')->move(public_path('image'), $url);
        $image = Image::query()->create([
            'url' => 'image/'.$url,
        ]);
        return response() -> json(['message'=>'Image upload success', 'id' => $image -> id]);
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
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request)
    {
        $image = Image::query() -> findOrFail($request -> image_id);
        $post = Post::query() -> findOrFail($request -> post_id);
        $post->images()->save($image);
//        $image -> update($request -> validated());
        return response() -> json(['message'=>'Update success']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($image)
    {
        if(auth() -> id() == Image::query() -> where('id', $image) -> first() -> user_id){
            $image -> delete();
            return response() -> json(['message' =>'Remove success']);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
