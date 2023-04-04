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
