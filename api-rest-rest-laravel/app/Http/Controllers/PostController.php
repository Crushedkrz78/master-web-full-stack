<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    public function index(){
        $post = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $post
        ], 200);
    }

    public function show($id){
        $post = Post::find($id)->load('category');

        if(is_object($post)){
            // Do Something
            $data = [
                'code' => 200,
                'status' => 'success',
                'posts' => $post
            ];
        }else{
            // Do Something
            $data = [
                'code' => 404,
                'status' => 'error',
                'posts' => 'La entrada no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }
}
