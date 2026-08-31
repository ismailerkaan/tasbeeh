@extends('admin.Masterpage')

@section('title', 'Admin | Test Kategorileri')

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
                        <h4 class="card-title mb-0">Test Kategorileri</h4>
                        <a href="{{ route('admin.test-categories.create') }}" class="btn btn-primary">Yeni Kategori</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Açıklama</th>
                                        <th>Seviye</th>
                                        <th>Sıra</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testCategories as $testCategory)
                                        <tr>
                                            <td>{{ $testCategory->name }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($testCategory->description, 80) }}</td>
                                            <td>{{ $testCategory->levels_count }}</td>
                                            <td>{{ $testCategory->sort_order }}</td>
                                            <td>
                                                @if ($testCategory->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.test-categories.edit', $testCategory) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.test-categories.destroy', $testCategory) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kategoriyi silmek istediğine emin misin? Bağlı seviyeler kategorisiz kalır.')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz test kategorisi yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $testCategories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection