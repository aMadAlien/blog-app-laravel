<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $userPosts = $request->input('user');

        $postsQuery = Post::orderBy('created_at', 'ASC');

        if (isset($userPosts)) {
            $postsQuery->where('author_id', auth()->id());
        }

        $posts = $postsQuery->simplePaginate(9);

        return response()->json($posts);
    }
}
