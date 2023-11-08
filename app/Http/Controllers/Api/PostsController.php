<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $numberOfPostsPerPage = 9;
        $userPosts = $request->input('user');

        $postsQuery = Post::orderBy('created_at', 'DESC');

        if (isset($userPosts)) {
            $postsQuery->where('author_id', auth()->id());
        }

        $posts = $postsQuery->paginate($numberOfPostsPerPage);

        return response()->json($posts);
    }

    public function destroy(Post $post)
    {
        if ($post->author_id == auth()->id()) {
            $post->delete();

            return response()->json(['message' => 'Post successfully deleted!']);
        }

        return response()->json([
            'message' => 'It is not possible to delete the post. Please verify the user rights or permissions to perform this action.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function update(Post $post, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        try {
            $post->title = $request->title;
            $post->description = $request->description;
            $post->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json(['message' => 'Post successfully updated!']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $newPost = Post::create([
            'author_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json([
            'post' => $newPost,
            'message' => 'Post successfully created!'
        ]);
    }

    public function show(Post $post)
    {
        return response()->json([
            'post' => array_merge(
                $post->toArray(),
                ['authorName' => $post->user->username]
            ),
            'message' => 'success'
        ]);
    }
}
