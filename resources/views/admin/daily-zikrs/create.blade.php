@extends('admin.Masterpage')

@section('title', 'Admin | Yeni Gunun Zikri')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Yeni Gunun Zikri</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.daily-zikrs.store') }}" method="POST">
                            @csrf
                            @include('admin.daily-zikrs._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
