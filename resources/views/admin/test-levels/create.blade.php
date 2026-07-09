@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Test Seviyesi')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Test Seviyesi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.test-levels.store') }}" method="POST">
                            @csrf
                            @include('admin.test-levels._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection