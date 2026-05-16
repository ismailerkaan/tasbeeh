@extends('admin.Masterpage')

@section('title', 'Admin | Hadis Kategorisi Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Hadis Kategorisi Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.hadis-categories.update', $hadisCategory) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.hadis-categories._form', ['hadisCategory' => $hadisCategory])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
