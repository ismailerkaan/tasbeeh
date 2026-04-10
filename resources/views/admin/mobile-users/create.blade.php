@extends('admin.Masterpage')

@section('title', 'Admin | Kullanıcı Ekle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Kullanıcı</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.mobile-users.store') }}" method="POST">
                            @csrf
                            @include('admin.mobile-users._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
