@extends('admin.Masterpage')

@section('title', 'Admin | Dualar')

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
                        <h4 class="card-title mb-0">Dualar</h4>
                        <a href="{{ route('admin.duas.create') }}" class="btn btn-primary">Yeni Dua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Kaynağı</th>
                                        <th>Dua</th>
                                        <th>Türkçe Meali</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($duas as $dua)
                                        <tr>
                                            <td>{{ $dua->category?->name ?? '-' }}</td>
                                            <td>{{ $dua->source }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($dua->dua, 80) }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($dua->turkce_meali, 80) }}</td>
                                            <td>
                                                @if ($dua->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.duas.edit', $dua) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.duas.destroy', $dua) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu duayı silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz dua yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $duas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
