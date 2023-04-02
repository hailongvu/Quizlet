<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);

            switch ($request->type) {
                case 'post':
                    $request->validate([
                        'class_id' => 'required|integer',
                        'name' => 'required|string|max:255',
                        'description' => 'required|string|max:1000',
                        'status' => 'nullable|in:active,pending,inactive',
                        'score_reporting' => 'nullable|in:0,1',
                        'tags' => 'nullable|string',
                    ]);
                    $post = new Post();
                    $post->user_id = auth()->user()->id;
                    $post->class_id = $request->input('class_id');
                    $post->name = $request->input('name');
                    $post->description = $request->input('description');
                    $post->status = $request->input('status', 'inactive');
                    $post->score_reporting = $request->input('score_reporting', 0);
                    $post->tags = $request->input('tags');
                    $post->save();

                    return response()->json(['message' => 'Post created', 'post' => $post], 200);
                    break;
                case 'comment':
                    $request->validate([
                        'post_id' => 'required|integer',
                        'comment' => 'required|string',
                    ]);

                    $comment = new Comment();
                    //user_id is auth()->user()->id
                    $comment->user_id = auth()->user()->id;
                    $comment->post_id = $request->input('post_id');
                    $comment->comment = $request->input('comment');
                    $comment->status = $request->input('status');
                    $comment->save();

                    return response()->json(['message' => 'Comment created', 'comment' => $comment], 200);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get post by id
        $post = Post::findOrFail($id);
        //get comments by post id
        $comments = Comment::where('post_id', $id)->get();
        //get user by user_id of post and comments
        $user = $post->user;
        $comments->each(function ($comment) {
            $comment->user;
        });
        //get class by class_id of post
        $class = $post->class;

        return response()->json([
            'post' => $post,
            'comments' => $comments,
            'user' => $user,
            'class' => $class,
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);
            
            switch ($request->type) {
                case 'post':
                    $request->validate([
                        'class_id' => 'required|integer',
                        'name' => 'required|string|max:255',
                        'description' => 'required|string|max:1000',
                        'status' => 'nullable|in:active,pending,inactive',
                        'score_reporting' => 'nullable|in:0,1',
                        'tags' => 'nullable|string',
                    ]);
                    
                    $post = Post::findOrFail($id);
                    $userRole = auth()->user()->role->name;
                    if (($userRole == 'teacher') || ($userRole == 'student' && auth()->user()->id == $post->user_id)){
                    $post->user_id = auth()->user()->id;
                    $post->class_id = $request->input('class_id', $post->class_id);
                    $post->name = $request->input('name');
                    $post->description = $request->input('description');
                    $post->status = $request->input('status');
                    $post->score_reporting = $request->input('score_reporting');
                    $post->tags = $request->input('tags');
                    $post->save();

                    return response()->json(['message' => 'Post updated', 'post' => $post], 200);
                    }
                    else{
                        return response()->json(['message' => 'You are not authorized to update this post'], 403);
                    }
                    break;
                case 'comment':
                    $request->validate([
                        'comment_id' => 'required|integer',
                        'comment' => 'nullable|string',
                        'status' => 'nullable|in:active,pending,inactive',
                    ]);
                    $comment = Comment::findOrFail($request->input('comment_id'));
                    $userRole = auth()->user()->role->name;
                    if (($userRole == 'teacher') || ($userRole == 'student' && auth()->user()->id == $comment->user_id)) {                    
                    $comment->user_id = $request->input('user_id');
                    //post_id is $id
                    $comment->post_id = $id;
                    $comment->comment = $request->input('comment', $comment->comment);
                    $comment->status = $request->input('status', $comment->status);
                    $comment->save();

                    return response()->json(['message' => 'Comment updated', 'comment' => $comment], 200);
                    } else {
                        return response()->json(['message' => 'You are not authorized to update this comment'], 403);
                    }
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);

            switch ($request->type) {
                case 'post':
                    $post = Post::findOrFail($id);
                    $userRole = auth()->user()->role->name;
                    if (($userRole == 'teacher') || ($userRole == 'student' && auth()->user()->id == $post->user_id)) {
                        $post->delete();
                        return response()->json(['message' => 'Post deleted'], 200);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You are not authorized to delete this post'
                        ], 403);
                    }
                    break;
                case 'comment':
                    $request->validate([
                        'comment_id' => 'required|integer',
                    ]);
                    $comment = Comment::findOrFail($request->input('comment_id'));
                    $userRole = auth()->user()->role->name;
                    if (($userRole == 'teacher') || ($userRole == 'student' && auth()->user()->id == $comment->user_id)) {
                        $comment->delete();
                        return response()->json(['message' => 'Comment deleted'], 200);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You are not authorized to delete this comment'
                        ], 403);
                    }
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
