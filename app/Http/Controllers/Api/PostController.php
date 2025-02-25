<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::included()->filter()
        ->sort()
        ->getOrPaginate();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|max:255',
            'slug'          => 'required|max:255|unique:posts',
            'extract'       => 'required',
            'content'       => 'required',
            'category_id'   => 'required|exists:categories,id',
        ]);

        $user = Auth::guard('api')->user();
        $data['user_id'] = $user->id;

        $post = Post::create($data);
        return PostResource::make($post);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::included()->findOrFail($id);
        return PostResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'name'          => 'required|max:255',
            'slug'          => 'required|max:255|unique:posts,slug,' . $post->id,
            'extract'       => 'required',
            'content'       => 'required',
            'category_id'   => 'required|exists:categories,id',
            'user_id'       => 'required|exists:users,id'
        ]);

        $post->update($request->all());
        return PostResource::make($post);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return PostResource::make($post);
    }
}
