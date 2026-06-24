@extends('admin.Masterpage')
@section('title', 'Admin | Yeni Özel Gün Paylaşımı')
@section('content')
<section><div class="card"><div class="card-header"><h4 class="card-title">Yeni Özel Gün Paylaşımı</h4></div><div class="card-body">
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.special-day-sharing-campaigns.store') }}">@csrf @include('admin.special-day-sharing-campaigns._form')</form>
</div></div></section>
@endsection
