@extends('admin.Masterpage')

@section('title', 'Admin | Bildirimler')

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
        @if (session('error'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Push Bildirimleri</h4>
                        <a href="{{ route('admin.push-notifications.create') }}" class="btn btn-primary">Yeni Bildirim</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Hedef</th>
                                        <th>Durum</th>
                                        <th>Başarılı</th>
                                        <th>Hatalı</th>
                                        <th>Hata Mesajı</th>
                                        <th>Gönderim Zamanı</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($notifications as $notification)
                                        <tr>
                                            <td>{{ $notification->title }}</td>
                                            <td>
                                                @if ($notification->target_type === 'all')
                                                    Tüm Kullanıcılar
                                                @else
                                                    Kullanıcı: {{ $notification->target_user_identifier }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($notification->status === 'sent')
                                                    <span class="badge bg-light-success text-success">Gönderildi</span>
                                                @elseif ($notification->status === 'failed')
                                                    <span class="badge bg-light-danger text-danger">Hata</span>
                                                @elseif ($notification->status === 'canceled')
                                                    <span class="badge bg-light-secondary text-secondary">İptal</span>
                                                @else
                                                    <span class="badge bg-light-warning text-warning">Kuyrukta</span>
                                                @endif
                                            </td>
                                            <td>{{ $notification->success_count }}</td>
                                            <td>{{ $notification->failed_count }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit((string) $notification->error_message, 110) ?: '-' }}</td>
                                            <td>{{ optional($notification->sent_at)->format('d.m.Y H:i') ?: '-' }}</td>
                                            <td class="text-end">
                                                @if ($notification->status === 'queued')
                                                    <a href="{{ route('admin.push-notifications.edit', $notification) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                    <form action="{{ route('admin.push-notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kuyruk kaydı iptal edilsin mi?')">İptal Et</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Henüz bildirim yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
