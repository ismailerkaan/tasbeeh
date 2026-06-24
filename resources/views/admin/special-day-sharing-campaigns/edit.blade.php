@extends('admin.Masterpage')
@section('title', 'Admin | Özel Gün Paylaşımı Düzenle')
@section('content')
<section><div class="card"><div class="card-header"><h4 class="card-title">Özel Gün Paylaşımı Düzenle</h4></div><div class="card-body">
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.special-day-sharing-campaigns.update', $campaign) }}">@csrf @method('PUT') @include('admin.special-day-sharing-campaigns._form')</form>
</div></div></section>
@endsection
