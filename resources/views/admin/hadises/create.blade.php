@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Hadis')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Hadis</h4>
                    </div>
                    <div class="card-body">
                        @if ($categories->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                <div class="alert-body">
                                    Önce en az bir aktif hadis kategorisi oluşturmalısın.
                                    <a href="{{ route('admin.hadis-categories.create') }}" class="alert-link">Kategori ekle</a>.
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.hadises.store') }}">
                                @csrf
                                @include('admin.hadises._form', ['categories' => $categories])
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
