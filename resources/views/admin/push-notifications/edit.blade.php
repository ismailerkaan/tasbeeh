@extends('admin.Masterpage')

@section('title', 'Admin | Bildirim Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Kuyruktaki Bildirimi Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.push-notifications.update', $pushNotification) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.push-notifications._form', ['pushNotification' => $pushNotification])

                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary">Güncelle</button>
                                <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-secondary">Vazgeç</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
