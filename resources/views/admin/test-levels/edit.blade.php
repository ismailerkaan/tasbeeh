@extends('admin.Masterpage')

@section('title', 'Admin | Test Seviyesi Duzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Test Seviyesi Duzenle</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-levels.update', $testLevel) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.test-levels._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection