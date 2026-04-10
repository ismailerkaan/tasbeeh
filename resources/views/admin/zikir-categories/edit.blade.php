@extends('admin.Masterpage')

@section('title', 'Admin | Zikir Kategorisi Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Zikir Kategorisi Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.zikir-categories.update', $zikirCategory) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.zikir-categories._form', ['zikirCategory' => $zikirCategory])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
