@extends('admin.Masterpage')

@section('title', 'Admin | Kullanıcı Detayı')

@section('content')
    @php
        $dailyActivity = collect($mobileUser->daily_activity_summary ?? [])
            ->filter(fn ($item) => is_array($item) && filled($item['date'] ?? null))
            ->sortByDesc('date')
            ->values();
    @endphp
    <section class="pb-1">
        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-25"><i data-feather="user" class="me-50"></i>Kullanıcı Detayı</h3>
                    <p class="text-muted mb-0">{{ $mobileUser->external_user_id }}</p>
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.mobile-users.edit', $mobileUser) }}" class="btn btn-primary">Düzenle</a>
                    <a href="{{ route('admin.mobile-users.index') }}" class="btn btn-outline-secondary">Listeye Dön</a>
                </div>
            </div>
        </div>

        <div class="row match-height mb-1">
            <div class="col-xl-3 col-md-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="repeat" class="me-50"></i>Kaç Zikir Çekmiş</small>
                        <h2 class="fw-bolder mb-0">{{ number_format((int) $mobileUser->total_zikir_count, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="book-open" class="me-50"></i>Okunan Zikir</small>
                        <h2 class="fw-bolder mb-0">{{ $mobileUser->read_zikirs_count }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="heart" class="me-50"></i>Okunan Dua</small>
                        <h2 class="fw-bolder mb-0">{{ $mobileUser->read_duas_count }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="smartphone" class="me-50"></i>Kayıtlı Cihaz</small>
                        <h2 class="fw-bolder mb-0">{{ $mobileUser->devices_count }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-5 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="info" class="me-50"></i>Profil Bilgileri</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <small class="text-muted d-block"><i data-feather="map-pin" class="me-50"></i>İl / İlçe</small>
                            <strong>{{ $mobileUser->city ?: '-' }} / {{ $mobileUser->district ?: '-' }}</strong>
                        </div>
                        <div class="mb-1">
                            <small class="text-muted d-block"><i data-feather="bell" class="me-50"></i>Bildirim İzni</small>
                            @if ($mobileUser->is_opt_in)
                                <span class="badge bg-light-success text-success">Açık</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary">Kapalı</span>
                            @endif
                        </div>
                        <div class="mb-1">
                            <small class="text-muted d-block"><i data-feather="log-in" class="me-50"></i>En Son Giriş</small>
                            <strong>{{ $mobileUser->last_login_at ? \Illuminate\Support\Carbon::parse($mobileUser->last_login_at)->format('d.m.Y H:i') : '-' }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block"><i data-feather="clock" class="me-50"></i>Son Senkron</small>
                            <strong>{{ $mobileUser->synced_at?->format('d.m.Y H:i') ?: '-' }}</strong>
                        </div>
                        <hr>
                        <div class="mb-1">
                            <small class="text-muted d-block"><i data-feather="layers" class="me-50"></i>İçerik Versiyonları</small>
                            <div class="d-flex flex-wrap gap-50 mt-50">
                                <span class="badge bg-light-primary text-primary">
                                    <i data-feather="repeat" class="me-25"></i>Zikir v{{ (int) ($mobileUser->zikir_version ?? 1) }}
                                </span>
                                <span class="badge bg-light-warning text-warning">
                                    <i data-feather="book-open" class="me-25"></i>Dua v{{ (int) ($mobileUser->dua_version ?? 1) }}
                                </span>
                                <span class="badge bg-light-info text-info">
                                    <i data-feather="clock" class="me-25"></i>Vakit v{{ (int) ($mobileUser->prayer_times_version ?? 1) }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-0">
                            <small class="text-muted d-block"><i data-feather="zap" class="me-50"></i>Seri Özeti</small>
                            <div class="d-flex flex-wrap gap-50 mt-50">
                                <span class="badge bg-light-success text-success">Mevcut: {{ (int) ($mobileUser->current_streak ?? 0) }}</span>
                                <span class="badge bg-light-primary text-primary">En iyi: {{ (int) ($mobileUser->best_streak ?? 0) }}</span>
                                <span class="badge bg-light-info text-info">Aktif gün: {{ (int) ($mobileUser->total_active_days ?? 0) }}</span>
                            </div>
                            <small class="text-muted d-block mt-50">Son aktif gün: {{ $mobileUser->last_active_date?->format('d.m.Y') ?: '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="book" class="me-50"></i>Son Çekilen Zikir</h4>
                    </div>
                    <div class="card-body">
                        @if ($mobileUser->lastZikir)
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <small class="text-muted d-block"><i data-feather="type" class="me-50"></i>Zikir Adı</small>
                                    <h5 class="mb-0">{{ $mobileUser->lastZikir->name }}</h5>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block"><i data-feather="tag" class="me-50"></i>İçerik ID</small>
                                    <strong>{{ $mobileUser->lastZikir->content_id }}</strong>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted d-block"><i data-feather="activity" class="me-50"></i>Toplam Çekim</small>
                            <h3 class="fw-bolder mb-0">{{ $mobileUser->lastZikir->count }}</h3>
                        @else
                            <p class="text-muted mb-0">Henüz son zikir verisi bulunmuyor.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="repeat" class="me-50"></i>Çektiği Zikirler</h4>
                        <span class="badge bg-light-primary text-primary">{{ count($readZikirItems) }} kayıt</span>
                    </div>
                    <div class="card-body">
                        @if ($readZikirItems !== [])
                            <div class="list-group" style="max-height: 360px; overflow-y: auto;">
                                @foreach ($readZikirItems as $item)
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-1">
                                            <h6 class="mb-25">
                                                <i data-feather="book" class="me-25"></i>
                                                {{ $item['title'] ?: 'Eşleşen zikir bulunamadı' }}
                                            </h6>
                                            <small class="text-muted">İçerik ID: {{ $item['content_id'] }}</small>
                                        </div>
                                        <div class="text-end">
                                            @if ($item['count'] !== null)
                                                <span class="badge bg-light-primary text-primary mb-25">{{ number_format((int) $item['count'], 0, ',', '.') }} adet</span>
                                            @endif
                                            <small class="text-muted d-block">{{ $item['created_at']?->format('d.m.Y H:i') ?: '-' }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Kullanıcının çektiği zikir listesi bulunmuyor.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="heart" class="me-50"></i>Okuduğu Dualar</h4>
                        <span class="badge bg-light-warning text-warning">{{ count($readDuaItems) }} kayıt</span>
                    </div>
                    <div class="card-body">
                        @if ($readDuaItems !== [])
                            <div class="list-group" style="max-height: 360px; overflow-y: auto;">
                                @foreach ($readDuaItems as $item)
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-1">
                                            <h6 class="mb-25">
                                                <i data-feather="bookmark" class="me-25"></i>
                                                {{ $item['title'] ? \Illuminate\Support\Str::limit($item['title'], 120) : 'Eşleşen dua bulunamadı' }}
                                            </h6>
                                            <small class="text-muted">İçerik ID: {{ $item['content_id'] }}</small>
                                        </div>
                                        <small class="text-muted">{{ $item['created_at']?->format('d.m.Y H:i') ?: '-' }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Kullanıcının okuduğu dua listesi bulunmuyor.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="calendar" class="me-50"></i>Seri Günlük Detay</h4>
                        <span class="badge bg-light-success text-success">{{ $dailyActivity->count() }} gün</span>
                    </div>
                    <div class="card-body">
                        @if ($dailyActivity->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tarih</th>
                                            <th>Toplam Zikir</th>
                                            <th>Günlük Hedef</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dailyActivity as $item)
                                            <tr>
                                                <td>{{ \Illuminate\Support\Carbon::parse($item['date'])->format('d.m.Y') }}</td>
                                                <td>{{ number_format((int) ($item['totalCount'] ?? 0), 0, ',', '.') }}</td>
                                                <td>
                                                    @if ((bool) ($item['completedDailyZikr'] ?? false))
                                                        <span class="badge bg-light-warning text-warning">Tamamlandı</span>
                                                    @else
                                                        <span class="badge bg-light-secondary text-secondary">Tamamlanmadı</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Bu kullanıcı için günlük seri aktivitesi henüz yok.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="smartphone" class="me-50"></i>Cihazlar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><i data-feather="smartphone" class="me-50"></i>Cihaz</th>
                                        <th><i data-feather="cpu" class="me-50"></i>Model / OS</th>
                                        <th><i data-feather="key" class="me-50"></i>Token</th>
                                        <th><i data-feather="check-circle" class="me-50"></i>Durum</th>
                                        <th><i data-feather="clock" class="me-50"></i>Son Görülme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mobileUser->devices as $device)
                                        <tr>
                                            <td>{{ $device->device_name ?: '-' }}</td>
                                            <td>{{ $device->device_model ?: '-' }} / {{ $device->os ?: '-' }} {{ $device->os_version ?: '' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($device->fcm_token, 36) }}</td>
                                            <td>
                                                @if ($device->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td>{{ $device->last_seen_at?->format('d.m.Y H:i') ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Kayıtlı cihaz bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
