@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Dua Kategorisi')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Dua Kategorisi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.dua-categories.store') }}">
                            @csrf
                            @include('admin.dua-categories._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
