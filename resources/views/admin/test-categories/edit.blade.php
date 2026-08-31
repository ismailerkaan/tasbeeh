@extends('admin.Masterpage')

@section('title', 'Admin | Test Kategorisi Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Test Kategorisi Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-categories.update', $testCategory) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.test-categories._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection