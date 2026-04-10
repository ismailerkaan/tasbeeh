@extends('admin.Masterpage')

@section('title', 'Admin | Kullanıcı Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Kullanıcı Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.mobile-users.update', $mobileUser) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.mobile-users._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
