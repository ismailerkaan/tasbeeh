@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Test Kategorisi')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Yeni Test Kategorisi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-categories.store') }}" method="POST">
                            @csrf
                            @include('admin.test-categories._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection