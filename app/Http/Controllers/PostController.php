<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
}
