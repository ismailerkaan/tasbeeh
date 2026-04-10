@extends('admin.Masterpage')

@section('title', 'Admin | Zikirler')

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
                        <h4 class="card-title mb-0">Zikirler</h4>
                        <a href="{{ route('admin.zikirs.create') }}" class="btn btn-primary">Yeni Zikir</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Zikir</th>
                                        <th>Anlamı</th>
                                        <th>Fazileti</th>
                                        <th>Hedef</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($zikirs as $zikir)
                                        <tr>
                                            <td>{{ $zikir->category?->name ?? '-' }}</td>
                                            <td>{{ $zikir->zikir }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($zikir->anlami, 70) }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($zikir->fazileti, 70) }}</td>
                                            <td>{{ $zikir->hedef }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.zikirs.edit', $zikir) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.zikirs.destroy', $zikir) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu zikri silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz zikir yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $zikirs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
