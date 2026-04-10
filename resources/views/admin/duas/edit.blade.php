@extends('admin.Masterpage')

@section('title', 'Admin | Dua Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Dua Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.duas.update', $dua) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.duas._form', ['dua' => $dua, 'categories' => $categories])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
