@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Bildirim')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Push Bildirimi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.push-notifications.store') }}">
                            @csrf
                            @include('admin.push-notifications._form')

                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary">Kuyruğa Al ve Gönder</button>
                                <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-secondary">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
