@extends('layouts.app')

@section('content')
    <div class="container">

        <form action="{{ url('/category') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('category.form', ['modo' => 'Crear'])
        </form>

    </div>
@endsection
