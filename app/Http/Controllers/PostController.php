<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     *  get post by filter
     */


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
    public function store(StorePostRequest $request)
    {
        $post = Post::query()->create([
            'content' => $request -> get('content')
        ]);
        return response() -> json(['message'=>'Post success']);
    }

    /**
     * Display the specified resource.
     */
    public function show($post)
    {
        $post = Post::query()->findOrFail($post);
        return response() -> json($post ->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $post)
    {
        $post = Post::query() -> findOrFail($post);
        $post -> update($request -> validated());
        return response() -> json(['message'=>'Update success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post)
    {
        $post = Post::query() -> findOrFail($post);
        $post -> delete();
        return response() -> json(['message' =>'remove success']);
    }

}
