@extends('admin.Masterpage')

@section('title', 'Admin | Versiyon Yönetimi')

@section('content')
    <section id="version-management">
        @if (session('status'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">{{ session('status') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Versiyon Yönetimi</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text mb-0">
                            Mobil uygulama açılışta sadece
                            <code>/api/v1/content/check</code>
                            endpointini çağırır. Versiyon farklıysa ilgili modül verisini çeker.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row match-height">
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text text-muted mb-25">Zikir İçerik Versiyonu</p>
                        <h3 class="fw-bolder mb-1">v{{ $contentVersion->zikir_version }}</h3>
                        <form method="POST" action="{{ route('admin.content-versions.bump') }}">
                            @csrf
                            <input type="hidden" name="module" value="zikir">
                            <button type="submit" class="btn btn-primary w-100">Yeni Zikir Yayınla</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text text-muted mb-25">Dua İçerik Versiyonu</p>
                        <h3 class="fw-bolder mb-1">v{{ $contentVersion->dua_version }}</h3>
                        <form method="POST" action="{{ route('admin.content-versions.bump') }}">
                            @csrf
                            <input type="hidden" name="module" value="dua">
                            <button type="submit" class="btn btn-primary w-100">Yeni Dua Yayınla</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text text-muted mb-25">Ezan İçerik Versiyonu</p>
                        <h3 class="fw-bolder mb-1">v{{ $contentVersion->prayer_times_version }}</h3>
                        <form method="POST" action="{{ route('admin.content-versions.bump') }}">
                            @csrf
                            <input type="hidden" name="module" value="prayer_times">
                            <button type="submit" class="btn btn-primary w-100">Ezan Verisini Yayınla</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
