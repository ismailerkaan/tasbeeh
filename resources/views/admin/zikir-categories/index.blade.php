@extends('admin.Masterpage')

@section('title', 'Admin | Zikir Kategorileri')

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
                        <h4 class="card-title mb-0">Zikir Kategorileri</h4>
                        <a href="{{ route('admin.zikir-categories.create') }}" class="btn btn-primary">Yeni Kategori</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kategori Adı</th>
                                        <th>Açıklama</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($zikirCategories as $zikirCategory)
                                        <tr>
                                            <td>{{ $zikirCategory->name }}</td>
                                            <td>{{ $zikirCategory->description ?: '-' }}</td>
                                            <td>
                                                @if ($zikirCategory->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.zikir-categories.edit', $zikirCategory) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.zikir-categories.destroy', $zikirCategory) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kategoriyi silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Henüz kategori yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $zikirCategories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
