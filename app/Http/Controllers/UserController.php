<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $req)
    {
        $post = new Post();
        $post->caption = $req->caption;
        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $post->file = $filename;
        }
        $post->user_id = Auth::id();

        $post->save();

        return response()->json(['status' => 'success', 'message' => 'Post added successfully']);
    }

    public function fetch()
    {
        $posts = Post::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'posts' => $posts]);
    }
}
