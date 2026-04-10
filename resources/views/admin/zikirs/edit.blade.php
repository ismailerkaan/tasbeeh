@extends('admin.Masterpage')

@section('title', 'Admin | Zikir Düzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Zikir Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.zikirs.update', $zikir) }}">
                            @csrf
                            @method('PUT')
                            @include('admin.zikirs._form', ['zikir' => $zikir, 'categories' => $categories])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
