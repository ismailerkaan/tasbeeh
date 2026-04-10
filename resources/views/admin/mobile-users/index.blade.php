@extends('admin.Masterpage')

@section('title', 'Admin | Kullanıcılar')

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
                        <h4 class="card-title mb-0">Kullanıcılar</h4>
                        <a href="{{ route('admin.mobile-users.create') }}" class="btn btn-primary">Yeni Kullanıcı</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kullanıcı ID</th>
                                        <th>Şehir / İlçe</th>
                                        <th>Bildirim</th>
                                        <th>Cihaz</th>
                                        <th>Toplam Zikir</th>
                                        <th>Okunan</th>
                                        <th>Son Senkron</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mobileUsers as $mobileUser)
                                        <tr>
                                            <td>{{ $mobileUser->external_user_id }}</td>
                                            <td>{{ $mobileUser->city ?: '-' }} / {{ $mobileUser->district ?: '-' }}</td>
                                            <td>
                                                @if ($mobileUser->is_opt_in)
                                                    <span class="badge bg-light-success text-success">Açık</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Kapalı</span>
                                                @endif
                                            </td>
                                            <td>{{ $mobileUser->devices_count }}</td>
                                            <td>{{ number_format((int) $mobileUser->total_zikir_count, 0, ',', '.') }}</td>
                                            <td>Zikir: {{ $mobileUser->read_zikirs_count }} / Dua: {{ $mobileUser->read_duas_count }}</td>
                                            <td>{{ $mobileUser->synced_at?->format('d.m.Y H:i') ?: '-' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.mobile-users.show', $mobileUser) }}" class="btn btn-sm btn-outline-info">Detay</a>
                                                <a href="{{ route('admin.mobile-users.edit', $mobileUser) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.mobile-users.destroy', $mobileUser) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Henüz kullanıcı yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $mobileUsers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
