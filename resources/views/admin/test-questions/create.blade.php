@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Test Sorusu')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Test Sorusu</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-questions.store') }}" method="POST">
                            @csrf
                            @include('admin.test-questions._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection