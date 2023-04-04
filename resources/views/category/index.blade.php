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
