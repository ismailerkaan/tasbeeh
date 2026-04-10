@extends('admin.Masterpage')

@section('title', 'Admin | Dua Kategorisi Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Dua Kategorisi Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.dua-categories.update', $duaCategory) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.dua-categories._form', ['duaCategory' => $duaCategory])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
