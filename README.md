# Proyecto blog sencillo

## Categorías

1. Crear modelo de categorías de blog.

```bash
PS E:\laravel\blog-ejemplo-tr> php artisan make:model Category -mcr

   INFO  Model [E:\laravel\blog-ejemplo-tr\app/Models/Category.php] created successfully.

   INFO  Migration [E:\laravel\blog-ejemplo-tr\database\migrations/2023_04_03_183238_create_categories_table.php] created successfully.

   INFO  Controller [E:\laravel\blog-ejemplo-tr\app/Http/Controllers/CategoryController.php] created successfully.
```

2. Definir la migración a tabla:

2023_04_03_183238_create_categories_table.php:

```php
public function up(): void
{
	Schema::create('categories', function (Blueprint $table) {
		$table->string('slug_categoria')->primary();
		$table->string('nombre_categoria');
	});
}
```

Models/Category.php:

```php
<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $primaryKey = 'slug_categoria';
    protected $keyType = 'string';
    public $incrementing = false;

}
```

3. Migrar:

```bash
php artisan migrate
```

4. Integrar rutas con vista y controlador:

web.php:

```php
<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

/* Route::get('/', function () {
    return view('welcome');
}); */


Route::resource('category', CategoryController::class)->middleware('auth'); // todas las rutas de category pasan por autorización

Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home', [CategoryController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [CategoryController::class, 'index'])->name('home');
});

```

category/index.blade.php:

```php
@extends('layouts.app')

@section('content')
    <div class="container">

        @if (Session::has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a class="btn btn-primary" href="{{ url('category/create') }}">Crear nueva categoría</a>

        @if (count($categories) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <th>{{ $category->slug_categoria }}</th>
                            <th>{{ $category->nombre_categoria }}</th>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <p class="mt-4">No tenemos categorías para mostrar</p>
        @endif
        {!! $categories->links() !!}
    </div>
@endsection
```

CategoryController.php:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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
    // ...
}
```

5. Crear método para crear nueva categoría:

CategoryController.php:

```php
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
```

Crear category/create.blade.php:

```php
@extends('layouts.app')

@section('content')
    <div class="container">

        <form action="{{ url('/category') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('category.form', ['modo' => 'Crear'])
        </form>

    </div>
@endsection
```

6. En layouts.app:

```php
<!-- Left Side Of Navbar -->
<ul class="navbar-nav me-auto">
	<a class="nav-link" href="{{ route('category.index') }}">{{ __('Categorías') }}</a> // <=========== layouts.app
</ul>
```

7. Formulario de adición de categoría:

```php
<h1>{{ $modo }} categoría</h1>

@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form>
    <div class="mb-3">
        <label for="nombre_categoria" class="form-label">Categoría</label>
        <input class="form-control" type="text" name="nombre_categoria"
            value="{{ isset($category->nombre_categoria) ? $category->nombre_categoria : old('nombre_categoria') }}"
            id="nombre_categoria">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

8. Borrar categoría:

category/index.blade.php:

```php
@extends('layouts.app')

@section('content')
    <div class="container">

        @if (Session::has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a class="btn btn-primary" href="{{ url('category/create') }}">Crear nueva categoría</a>

        @if (count($categories) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <th>{{ $category->slug_categoria }}</th>
                            <th>{{ $category->nombre_categoria }}</th>
                            <th>
                                <form class="d-inline"
                                    action="{{ route('category.destroy', ['category' => $category->slug_categoria]) }}"
                                    method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input class="btn btn-danger" type="submit"
                                        onclick="return confirm('¿Realmente deseas borrar?')" value="Borrar">
                                </form>
                            </th>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <p class="mt-4">No tenemos categorías para mostrar</p>
        @endif
        {!! $categories->links() !!}
    </div>
@endsection
```

CategoryController.php:

```php
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
```

## Posts

1. Crear:

```bash
PS E:\laravel\blog-ejemplo-tr> php artisan make:model Post -mcr

   INFO  Model [E:\laravel\blog-ejemplo-tr\app/Models/Post.php] created successfully.

   INFO  Migration [E:\laravel\blog-ejemplo-tr\database\migrations/2023_04_04_011836_create_posts_table.php] created successfully.

   INFO  Controller [E:\laravel\blog-ejemplo-tr\app/Http/Controllers/PostController.php] created successfully.
```

2. Definir migración de la tabla de posts:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('post_id')->primary();
            $table->string("title");
            $table->longText("content");
            $table->longText('category'); // en realidad, es un array de strings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

Migrar:
```bash
php artisan migrate
```

3. Modificar modelo de Posts:

```php
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
```

4. En web.php, agregar:

```php
Route::resource('post', PostController::class)->middleware('auth');
```

5. Crear las vistas para los posts

post/index.blade.php:
```php
@extends('layouts.app')

@section('content')
    <div class="container">

        @if (Session::has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a class="btn btn-primary" href="{{ url('post/create') }}">Crear nuevo post</a>

        @if (count($posts) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Contenido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <th>{{ $post->post_id }}</th>
                            <th>{{ $post->title }}</th>
                            <th>{{ $post->content }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mt-4">No tenemos posts para mostrar</p>
        @endif

    </div>
@endsection
```

post/create.blade.php:
```php
@extends('layouts.app')

@section('content')
    <div class="container">

        <form action="{{ url('/post') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('post.form', ['modo' => 'Crear'])
        </form>

    </div>
@endsection
```

post/form.blade.php:
```php
{{-- Inyección de clase --}}
@inject('categories', App\Http\Controllers\PostController::class)

<h1>{{ $modo }} post</h1>

@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
{{-- {{$categories->listCategories()}} --}}
<form>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input class="form-control" type="text" name="title"
            value="{{ isset($post->title) ? $post->title : old('title') }}" id="title">
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Contenido</label>
        <textarea name="content" class="form-control" value="{{ isset($post->content) ? $post->content : old('content') }}" id="content"
            rows="10" style="resize: none;"></textarea>
    </div>
    <div class="mb-3">
        <h4>Escoge las categorías del post</h4>
        @if (count($categories->listCategories()) > 0)
            @foreach ($categories->listCategories() as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                    value="{{ isset($category->slug_categoria) ? $category->slug_categoria : old('slug_categoria') }}"
                    id="{{$category}}" name="category[]">
                    <label class="form-check-label" for="{{$category}}">
                        {{ $category->nombre_categoria }}
                    </label>
                </div>
            @endforeach
        @else
            <p>No hay categorías para colocar</p>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

## Posts + Usuarios (relación muchos a muchos)

1. Crear la migración:

```bash
PS E:\laravel\blog-ejemplo-tr> php artisan make:migration create_user_post_table

   INFO  Migration [E:\laravel\blog-ejemplo-tr\database\migrations/2023_04_04_220038_create_user_post_table.php] created successfully.
```
2. Desarrollar la tabla intermedia:

create_user_post_table.php:

```php
public function up(): void
{
    Schema::create('user_post', function (Blueprint $table) {
        //$table->id();
        $table->primary(['post_id','user_id']);
        $table->bigInteger('user_id')->unsigned();
        $table->uuid('post_id');
        $table->string('note'); // para pivotar
        $table->timestamps();

        $table->foreign('post_id')
            ->references('post_id')
            ->on('posts');
            $table->foreign('user_id')
            ->references('id')
            ->on('users');
    });
}
```







