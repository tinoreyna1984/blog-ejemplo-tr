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
