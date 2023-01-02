<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'getImage',
            'getPostsByCategory',
            'getPostsByUser'
        ]]);
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

    public function store(Request $request){
        // Recoger datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            // Conseguir el usuario identificado
            $user = $this->getIdentity($request);

            // Validar los datos recibidos
            $validate = \Validator::make3($params_array,[
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos'
                ];
            }else{
                // Guardar el artículo
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => $post
                ];
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Envía los datos correctamente'
            ];
        }

        // Devolver una respuesta
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request){
        // Recoger los datos por Post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Datos a devolver
        $data = array(
            'code' => 400,
            'status' => 'error',
            'post' => 'Datos enviados incorrectamente'
        );

        if(!empty($params_array)){
                // Validar lso datos recibidos
                $validate = \Validator::make($params_array, [
                    'title' => 'required',
                    'content' => 'required',
                    'category_id' => 'required'
                ]);

                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
                // Eliminar los datos que no se van a actualizar
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['create_at']);
                unset($params_array['user']);

                // Conseguir usuario identificado
                $user = $this->getIdentity($request);

                // Búsqueda del registro especificado
                $post = Post::find($id);

                // Actualización del registro especificado
                if(!empty($post) && is_object($post)){
                    $post_update = Post::where('id', $id)
                                ->where('user_id', $user->sub)
                                ->update($params_array);
                    if(!empty($post_update)){
                        $data = array(
                            'code' => 200,
                            'status' => 'success',
                            'original_post' => $post,
                            'changes' => $params_array
                        );
                    }else{
                        $data = array(
                            'code' => 400,
                            'status' => 'error',
                            'message' => 'Imposible actualizar Post debido a que no eres propietario',
                        );
                    }
                }else{
                    $data = array(
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'El post que intentas actualizar no existe',
                    );
                }
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        // Obtener el usuario identificado
        $user = $this->getIdentity($request);

        // Obtener el registro que se va a eliminar
        $post = Post::where('id', $id)
                    ->where('user_id', $user->sub)->first();

        if(!empty($post)){
            // Eliminar el registro
            $post->delete();

            // Devolver una respuesta
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        }else{
            // Devolver una respuesta
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    private function getIdentity($request){
        // Conseguir usuario identificado
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request){
        // Recoger la imagen de la petición de archivo
        $image = $request->file('file0');

        // Validación de imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,gif,png'
        ]);

        // Guardar la imagen
        if(!$image || $validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            ];
        }else{
            $image_name = time().$image->getClientOriginalName();

            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            ];
        }

        // Devolver datos
        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        // Comprobar si existe el archivo
        $isset = \Storage::disk('images')->exists($filename);

        if($isset){
            // Obtener la imagen
            $file = \Storage::disk('images')->get($filename);

            // Devolver la imagen
            return new Response($file, 200);
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function getPostsByCategory($id){
        //
        $posts = Post::where('category_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

    public function getPostsByUser($id){
        $posts = Post::where('user_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

}
