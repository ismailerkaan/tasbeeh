@extends('admin.Masterpage')

@section('title', 'Admin | Dini Özel Gün Düzenle')

@section('content')
    <section><div class="card"><div class="card-header"><h4 class="card-title">Dini Özel Gün Düzenle</h4></div><div class="card-body">
        <form method="POST" action="{{ route('admin.religious-special-days.update', $specialDay) }}">@csrf @method('PUT') @include('admin.religious-special-days._form')</form>
    </div></div></section>
@endsection
