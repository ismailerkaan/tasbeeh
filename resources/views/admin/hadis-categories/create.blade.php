@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Hadis Kategorisi')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Hadis Kategorisi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.hadis-categories.store') }}">
                            @csrf
                            @include('admin.hadis-categories._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
