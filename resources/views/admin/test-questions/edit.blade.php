@extends('admin.Masterpage')

@section('title', 'Admin | Test Sorusu Duzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Test Sorusu Duzenle</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-questions.update', $testQuestion) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.test-questions._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection