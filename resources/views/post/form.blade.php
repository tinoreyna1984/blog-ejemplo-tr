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
