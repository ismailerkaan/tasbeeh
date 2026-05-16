@extends('admin.Masterpage')

@section('title', 'Admin | Hadisler')

@section('content')
    <section>
        @if (session('status'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">{{ session('status') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Hadisler</h4>
                        <a href="{{ route('admin.hadises.create') }}" class="btn btn-primary">Yeni Hadis</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Kaynağı</th>
                                        <th>Hadis</th>
                                        <th>Türkçe Meali</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hadises as $hadis)
                                        <tr>
                                            <td>{{ $hadis->category?->name ?? '-' }}</td>
                                            <td>{{ $hadis->source }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($hadis->hadis, 80) }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($hadis->turkce_meali, 80) }}</td>
                                            <td>
                                                @if ($hadis->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.hadises.edit', $hadis) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.hadises.destroy', $hadis) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu hadisi silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz hadis yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $hadises->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
