<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
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
        $post = Post::all();
        return view('page.post.form', ['posts' => $post]);
    }

    /**
     *  get post by filter
     */
    public function filter(FilterRequest $request)
    {
        $posts = Post::query();
        if ($request->has('user_id'))
            $posts = $posts->where("user_id", "==", $request->user_id);
        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "desc";
        }

        $posts = $posts->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10,'*',
                'page', $request->page ?? 0);

        $response = [
            "data" => array(),
            "current_page" => $posts->currentPage(),
            "last_page" => $posts->lastPage(),
            "per_page" => $posts->perPage(),
            "total" => $posts->total()
        ];

        foreach($posts -> items() as $post){
            $response['data'][] = $post -> toArray();
        }
        return response() -> json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.post.form');
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
        return view('editpost');
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
        if(auth() -> id() == Post::query() -> where('id', $this -> route('post')) -> first() -> user_id){
            $post = Post::query() -> findOrFail($post);
            $post -> delete();
        }
        return response() -> json(['message' =>'remove success']);
    }

}
