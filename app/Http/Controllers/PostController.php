<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', ['posts' => Post::with('user')->latest()->get(),]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $request->user()->posts()->create($validated);

        return redirect(route('posts.index'));
    }

    public function edit(Post $posts)
    {
        return view('posts.edit', ['posts' => $posts]);
    }

    public function update(Request $request, Post $posts)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $posts->update($validated);

        return redirect(route('posts.index'));
    }

    public function delete(Post $posts)
    {
        $posts->delete();

        return redirect(route('posts.index'));
    }
}
