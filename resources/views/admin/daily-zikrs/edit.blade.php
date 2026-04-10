@extends('admin.Masterpage')

@section('title', 'Admin | Gunun Zikri Duzenle')

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Gunun Zikri Duzenle</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.daily-zikrs.update', $dailyZikr) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.daily-zikrs._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
