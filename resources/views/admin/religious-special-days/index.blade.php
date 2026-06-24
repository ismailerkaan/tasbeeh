@extends('admin.Masterpage')

@section('title', 'Admin | Dini Özel Günler')

@section('content')
    <section>
        @if (session('status'))
            <div class="alert alert-success" role="alert"><div class="alert-body">{{ session('status') }}</div></div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-50">Dini Özel Günler</h4>
                    <p class="text-muted mb-0">Mobil uygulamada yayınlanacak dini günleri yönetin.</p>
                </div>
                <a href="{{ route('admin.religious-special-days.create') }}" class="btn btn-primary">Yeni Özel Gün</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Tarih</th><th>Günün Adı</th><th>Kategori</th><th>Hicri Tarih</th><th>Öneri</th><th>Durum</th><th class="text-end">İşlem</th></tr></thead>
                        <tbody>
                            @forelse ($specialDays as $specialDay)
                                <tr>
                                    <td>{{ $specialDay->event_date->format('d.m.Y') }}</td>
                                    <td>{{ $specialDay->title }}</td>
                                    <td>{{ \App\Models\ReligiousSpecialDay::CATEGORIES[$specialDay->category]['label'] ?? '-' }}</td>
                                    <td>{{ $specialDay->hijri_date ?: '-' }}</td>
                                    <td>{{ count($specialDay->recommendations ?? []) }} madde</td>
                                    <td>
                                        <span class="badge {{ $specialDay->is_active ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }}">
                                            {{ $specialDay->is_active ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.religious-special-days.edit', $specialDay) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                        <form action="{{ route('admin.religious-special-days.destroy', $specialDay) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu dini özel günü silmek istediğinize emin misiniz?')">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Henüz dini özel gün eklenmedi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $specialDays->links() }}</div>
            </div>
        </div>
    </section>
@endsection
