<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::paginate(10);
        return view('post.index', ['posts' => $posts]);
        //dd($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $campos = [
            'title' => 'required|string',
            'content' => 'required|string',
            'category' => 'required'
        ];
        $mensajes = [
            'required' => 'El :attribute es requerido',
        ];

        // valida el request
        $this->validate($request, $campos, $mensajes);

        // toma todos los valores del request excepto el token
        $datosPost = $request->except('_token');
        $datosPost['post_id'] = Str::uuid()->toString();
        $datosPost['category'] = json_encode($request->input('category'));  // array de categorías
        //dd($request, $this, $datosPost);

        Post::insert($datosPost);
        return redirect('post')->with('mensaje', 'Empleado agregado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public static function listCategories(){
        return Category::all();
    }

    public function getPosts(){
        return response()->json(Post::all(), 200);
    }
}
