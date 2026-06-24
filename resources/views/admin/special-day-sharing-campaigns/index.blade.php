@extends('admin.Masterpage')

@section('title', 'Admin | Özel Gün Paylaşımları')

@section('content')
<section>
    @if (session('status'))<div class="alert alert-success"><div class="alert-body">{{ session('status') }}</div></div>@endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-50">Özel Gün Paylaşımları</h4>
                <p class="text-muted mb-0">Uygulama açılışında gösterilecek, zamanlanmış görsel paylaşımlarını yönetin.</p>
            </div>
            <a href="{{ route('admin.special-day-sharing-campaigns.create') }}" class="btn btn-primary">Yeni Paylaşım</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Yayın Tarihi</th><th>Başlık</th><th>Metin</th><th>Görsel</th><th>Durum</th><th class="text-end">İşlem</th></tr></thead>
                    <tbody>
                    @forelse ($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->publish_date->format('d.m.Y') }}</td>
                            <td>{{ $campaign->title }}</td>
                            <td>{{ str($campaign->message)->limit(70) }}</td>
                            <td>{{ $campaign->images_count }} adet</td>
                            <td><span class="badge {{ $campaign->is_active ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }}">{{ $campaign->is_active ? 'Aktif' : 'Pasif' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.special-day-sharing-campaigns.edit', $campaign) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                <form action="{{ route('admin.special-day-sharing-campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu paylaşımı silmek istediğinize emin misiniz?')">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Henüz özel gün paylaşımı oluşturulmadı.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $campaigns->links() }}</div>
        </div>
    </div>
</section>
@endsection
