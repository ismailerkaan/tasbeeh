@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Dini Özel Gün')

@section('content')
    <section><div class="card"><div class="card-header"><h4 class="card-title">Yeni Dini Özel Gün</h4></div><div class="card-body">
        <form method="POST" action="{{ route('admin.religious-special-days.store') }}">@csrf @include('admin.religious-special-days._form')</form>
    </div></div></section>
@endsection
