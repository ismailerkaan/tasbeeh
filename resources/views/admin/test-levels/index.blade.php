@extends('admin.Masterpage')

@section('title', 'Admin | Test Seviyeleri')

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
                        <h4 class="card-title mb-0">Test Seviyeleri</h4>
                        <a href="{{ route('admin.test-levels.create') }}" class="btn btn-primary">Yeni Seviye</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Seviye</th>
                                        <th>Açıklama</th>
                                        <th>Soru</th>
                                        <th>Sıra</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testLevels as $testLevel)
                                        <tr>
                                            <td>{{ $testLevel->name }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($testLevel->description, 80) }}</td>
                                            <td>{{ $testLevel->questions_count }}</td>
                                            <td>{{ $testLevel->sort_order }}</td>
                                            <td>
                                                @if ($testLevel->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.test-levels.edit', $testLevel) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.test-levels.destroy', $testLevel) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu seviyeyi ve bağlı soruları silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz test seviyesi yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $testLevels->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection