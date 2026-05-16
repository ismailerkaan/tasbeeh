@extends('admin.Masterpage')

@section('title', 'Admin | Hadis Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Hadis Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.hadises.update', $hadis) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.hadises._form', ['hadis' => $hadis, 'categories' => $categories])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
