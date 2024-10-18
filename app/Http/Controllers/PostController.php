<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function createPost(Request $request){

        return view('post.form');

    }


    public function savePost(Request $request){

            $request->validate([
                'post_value' => ['required']
            ]);
           
            $post =new Post;
            $post->user_id =Auth::user()->id;
            $post->post =$request->post_value;
            $post->save();

           $response['success'] = true;
           $response['message'] = 'New Post Created Successfully';
           $response['redirection'] = true;
           $response['redirectUrl'] = route('welcome');

           return response($response,200);
    }

          public function storeComment(Request $request, Post $post)
        {
            $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            $comment =new Comment;
            $comment->post_id = $post->id;
            $comment->user_id = auth()->id();
            $comment->comment = $request->comment;
            $comment->save();


            return back()->with('success', 'Comment added successfully.');
        }


        public function likePost(Post $post)
        {
            $like = PostLike::where('post_id', $post->id)->where('user_id', auth()->id())->first();

            if ($like) {
                $like->delete();
                $response['success'] = false;
                $response['message'] = 'Like Removed successfully';
            } else {
                $postLike =new PostLike;
                $postLike->post_id = $post->id;
                $postLike->user_id = auth()->id();
                $postLike->save();
                $response['success'] = true;
                $response['message'] = 'Like given successfully';
            }

           
           
          

            return response()->json($response);
        }
}
