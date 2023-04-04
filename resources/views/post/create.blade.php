@extends('layouts.app')

@section('content')
    <div class="container">

        <form action="{{ url('/post') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('post.form', ['modo' => 'Crear'])
        </form>

    </div>
@endsection
