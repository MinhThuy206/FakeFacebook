<?php

namespace App\Http\Controllers;

use App\Enums\ImageStatus;
use App\Http\Requests\Post\FilterRequest;
use App\Http\Requests\Post\StoreImageRequest;
use App\Http\Requests\Post\UpdateAvatarImage;
use App\Http\Requests\Post\UpdateImageRequest;
use App\Models\Album;
use App\Models\AlbumData;
use App\Models\AlbumMeta;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        $url = Carbon::now()->timestamp . '.' . $request->file('image')->extension();
//        $request ->file('image') -> storeAs('image', $url,['disk'=> 'public']);
        $request->file('image')->move(public_path('image'), $url);
        $image = Image::query()->create([
            'url' => 'image/' . $url,
        ]);

        return response()->json(['message' => 'Image upload success', 'id' => $image->id]);
    }

    /**
     * Store avatar images
     */
    public function storeAvt($avatar)
    {
        $user = auth()->user();
        $user->avatar_id = $avatar;
        $user->save();
        $avatar_image = AlbumMeta::query()->where('user_id', $user->id)->where('name', 'avatar')->first();
        if ($avatar_image) {
            AlbumData::query()->insert([
                "image_id" => $avatar,
                "album_id" => $avatar_image->id
            ]);
            return response()->json(['message' => 'Avatar image upload success', 'avatar' => $avatar]);
        } else {
            $image = AlbumMeta::query()->create([
                "name" => "avatar",
                "user_id" => $user->id,
            ]);

            AlbumData::query()->insert([
                "image_id" => $avatar,
                "album_id" => $image->id
            ]);
            return response()->json(['message' => 'Avatar image not found'], 404);
        }
    }

    /**
     * Filter avatar images
     */
    public function filterAvatarImage(FilterRequest $request)
    {
        $user = auth()->user();

        $album = AlbumMeta::query()
            ->where('user_id', $user->id)
            ->where('name', 'avatar')
            ->first();

        $images = [];

        if ($album) {
            $imagesQuery = AlbumData::query()->where('album_id', $album->id);

            if ($request->has('user_id')) {
                $imagesQuery->where('user_id', $request->user_id);
            }
            if (!$request->has('orderBy')) {
                $request->orderBy = "created_at";
            }
            if (!$request->has('order')) {
                $request->order = "desc";
            }

            $images = $imagesQuery->orderBy($request->orderBy, $request->order)
                ->paginate($request->size ?? 10, '*',
                    'page', $request->page ?? 0);
        }

        if ($images instanceof LengthAwarePaginator) {
            $responseData = [
                "data" => $images->items(),
                "current_page" => $images->currentPage(),
                "last_page" => $images->lastPage(),
                "per_page" => $images->perPage(),
                "total" => $images->total()
            ];
        } else {
            $responseData = [
                "data" => [],
                "current_page" => 0,
                "last_page" => 0,
                "per_page" => 0,
                "total" => 0
            ];
        }

        return response()->json($responseData);
    }

    /**
     * Store cover images
     */
    public function storeCover($cover)
    {
        $user = auth()->user();
        $user->cover_id = $cover;
        $user->save();
        $cover_image = AlbumMeta::query()->where('user_id', $user->id)->where('name', 'cover')->first();
        if ($cover_image) {
            AlbumData::query()->insert([
                "image_id" => $cover,
                "album_id" => $cover_image->id
            ]);
            return response()->json(['message' => 'Cover image upload success', 'cover'=>$cover]);
        } else {
            $image = AlbumMeta::query()->create([
                "name" => "cover",
                "user_id" => $user->id,
            ]);

            AlbumData::query()->insert([
                "image_id" => $cover,
                "album_id" => $image->id
            ]);
            return response()->json(['message' => 'Cover image upload success', 'cover'=>$cover]);
        }
    }

    /**
     * Filter cover images
     */
    public function filterCoverImage(FilterRequest $request)
    {
        $user = auth()->user();

        $album = AlbumMeta::query()
            ->where('user_id', $user->id)
            ->where('name', 'cover')
            ->first();

        $images = [];

        if ($album) {
            $imagesQuery = AlbumData::query()->where('album_id', $album->id);

            if ($request->has('user_id')) {
                $imagesQuery->where('user_id', $request->user_id);
            }
            if (!$request->has('orderBy')) {
                $request->orderBy = "created_at";
            }
            if (!$request->has('order')) {
                $request->order = "desc";
            }

            $images = $imagesQuery->orderBy($request->orderBy, $request->order)
                ->paginate($request->size ?? 10, '*',
                    'page', $request->page ?? 0);
        }

        if ($images instanceof LengthAwarePaginator) {
            $responseData = [
                "data" => $images->items(),
                "current_page" => $images->currentPage(),
                "last_page" => $images->lastPage(),
                "per_page" => $images->perPage(),
                "total" => $images->total()
            ];
        } else {
            $responseData = [
                "data" => [],
                "current_page" => 0,
                "last_page" => 0,
                "per_page" => 0,
                "total" => 0
            ];
        }

        return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     */
    public function show($image)
    {
        $image = Image::query()->findOrFail($image);
        return response()->json($image->toArray());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request)
    {
        $image = Image::query()->findOrFail($request->image_id);
        $post = Post::query()->findOrFail($request->post_id);
        $post->images()->save($image);
        return response()->json(['message' => 'Update success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($image)
    {
        if (auth()->id() == Image::query()->where('id', $image)->first()->user_id) {
            $image->delete();
            return response()->json(['message' => 'Remove success']);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
