<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories["categories"] = Category::paginate(10); // obtener de la tabla de categorías
        return view('category.index', $categories); // enviar categorías
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // criterios de validación previa de los campos
        $campos = [
            'nombre_categoria' => 'required|string|max:200',
        ];
        $mensajes = [
            'required' => 'El :attribute es requerido',
        ];

        // valida el request
        $this->validate($request, $campos, $mensajes);

        // toma todos los valores del request excepto el token
        $datosCategoria = $request->except('_token');
        $datosCategoria["slug_categoria"] = Str::slug($request->nombre_categoria, '-');

        Category::insert($datosCategoria); // inserta en tabla

        return redirect('category')->with('mensaje', 'Empleado agregado exitosamente');
        //dd($request, $this, $datosCategoria);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug_categoria)
    {
        //
        $category = Category::findOrFail($slug_categoria);
        //dd($category);
        if ($category) {
            Category::destroy($slug_categoria);
        }
        return redirect()->route('category.index')->with('mensaje', 'Categoría eliminada exitosamente');
    }
}
