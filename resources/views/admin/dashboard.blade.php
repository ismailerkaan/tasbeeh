@extends('admin.Masterpage')

@section('title', 'Admin | Dashboard')

@section('content')
    <section id="dashboard-analytics">
        <div class="row match-height">
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="users" class="me-50"></i>Toplam Kullanıcı</small>
                        <h2 class="fw-bolder mb-0">{{ number_format((int) $stats['mobile_users'], 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="bell" class="me-50"></i>Bildirim İzin Oranı</small>
                        <h2 class="fw-bolder mb-0">%{{ number_format((float) $stats['opt_in_rate'], 1, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="repeat" class="me-50"></i>Toplam Zikir Çekimi</small>
                        <h2 class="fw-bolder mb-0">{{ number_format((int) $stats['total_zikir_count'], 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted d-block"><i data-feather="book-open" class="me-50"></i>Toplam Okunan Dua</small>
                        <h2 class="fw-bolder mb-0">{{ number_format((int) $stats['total_read_duas'], 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="trending-up" class="me-50"></i>7 Günlük Kullanıcı ve Senkron Trendleri</h4>
                        <span class="badge bg-light-primary text-primary">Canlı Veri</span>
                    </div>
                    <div class="card-body">
                        <div id="usageTrendChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="send" class="me-50"></i>Bildirim Kuyruğu</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1 d-flex justify-content-between">
                            <span class="text-muted">Queued</span>
                            <strong>{{ number_format((int) $stats['queued_notifications'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <span class="text-muted">Sent</span>
                            <strong class="text-success">{{ number_format((int) $stats['sent_notifications'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <span class="text-muted">Failed</span>
                            <strong class="text-danger">{{ number_format((int) $stats['failed_notifications'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Canceled</span>
                            <strong class="text-secondary">{{ number_format((int) $stats['canceled_notifications'], 0, ',', '.') }}</strong>
                        </div>
                        <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-primary w-100">Kuyruğu Yönet</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row match-height">
            <div class="col-lg-6 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="map-pin" class="me-50"></i>En Çok Kullanılan Şehirler</h4>
                    </div>
                    <div class="card-body">
                        <div id="cityDistributionChart" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="pie-chart" class="me-50"></i>Platform Dağılımı</h4>
                    </div>
                    <div class="card-body">
                        <div id="platformDistributionChart" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="bar-chart-2" class="me-50"></i>En Çok Okunan 10 Zikir</h4>
                        <span class="badge bg-light-primary text-primary">Toplam Adet</span>
                    </div>
                    <div class="card-body">
                        <div id="topZikirChart" style="min-height: 340px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row match-height">
            <div class="col-lg-7 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="shield" class="me-50"></i>Versiyon Uyum Durumu</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Zikir v{{ $contentVersion->zikir_version }}</span>
                                <span>%{{ number_format((float) $versionAdoption['zikir']['up_to_date_rate'], 1, ',', '.') }}</span>
                            </div>
                            <div class="progress progress-bar-primary mt-50" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $versionAdoption['zikir']['up_to_date_rate'] }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format((int) $versionAdoption['zikir']['up_to_date_count'], 0, ',', '.') }} güncel, {{ number_format((int) $versionAdoption['zikir']['outdated_count'], 0, ',', '.') }} eski</small>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Dua v{{ $contentVersion->dua_version }}</span>
                                <span>%{{ number_format((float) $versionAdoption['dua']['up_to_date_rate'], 1, ',', '.') }}</span>
                            </div>
                            <div class="progress progress-bar-warning mt-50" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $versionAdoption['dua']['up_to_date_rate'] }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format((int) $versionAdoption['dua']['up_to_date_count'], 0, ',', '.') }} güncel, {{ number_format((int) $versionAdoption['dua']['outdated_count'], 0, ',', '.') }} eski</small>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between">
                                <span>Namaz Vakti v{{ $contentVersion->prayer_times_version }}</span>
                                <span>%{{ number_format((float) $versionAdoption['prayer_times']['up_to_date_rate'], 1, ',', '.') }}</span>
                            </div>
                            <div class="progress progress-bar-info mt-50" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $versionAdoption['prayer_times']['up_to_date_rate'] }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format((int) $versionAdoption['prayer_times']['up_to_date_count'], 0, ',', '.') }} güncel, {{ number_format((int) $versionAdoption['prayer_times']['outdated_count'], 0, ',', '.') }} eski</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i data-feather="database" class="me-50"></i>İçerik Özeti</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Zikir Kategori</span>
                            <strong>{{ number_format((int) $stats['zikir_categories'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Toplam Zikir</span>
                            <strong>{{ number_format((int) $stats['zikirs'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Dua Kategori</span>
                            <strong>{{ number_format((int) $stats['dua_categories'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Aktif Dua</span>
                            <strong>{{ number_format((int) $stats['duas'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="border-top pt-1">
                            <small class="text-muted d-block mb-25">Son senkron:</small>
                            <strong>{{ $stats['last_sync_at'] ? \Illuminate\Support\Carbon::parse($stats['last_sync_at'])->format('d.m.Y H:i') : '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i data-feather="award" class="me-50"></i>En Aktif Kullanıcılar</h4>
                        <a href="{{ route('admin.mobile-users.index') }}" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kullanıcı</th>
                                        <th>İl / İlçe</th>
                                        <th>Toplam Zikir</th>
                                        <th>Son Zikir</th>
                                        <th>Son Senkron</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topUsers as $user)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.mobile-users.show', $user) }}" class="fw-bolder">
                                                    {{ \Illuminate\Support\Str::limit($user->external_user_id, 26) }}
                                                </a>
                                            </td>
                                            <td>{{ $user->city ?: '-' }} / {{ $user->district ?: '-' }}</td>
                                            <td>{{ number_format((int) $user->total_zikir_count, 0, ',', '.') }}</td>
                                            <td>{{ $user->lastZikir?->name ?: '-' }}</td>
                                            <td>{{ $user->synced_at?->format('d.m.Y H:i') ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Henüz kullanıcı verisi bulunmuyor.</td>
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

@push('scripts')
    <script src="{{ asset('assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script>
        (() => {
            const colors = {
                primary: '#7367F0',
                success: '#28C76F',
                warning: '#FF9F43',
                info: '#00CFE8',
                muted: '#82868b',
            };

            const trendElement = document.querySelector('#usageTrendChart');
            if (trendElement) {
                const trendChart = new ApexCharts(trendElement, {
                    chart: { type: 'area', height: 320, toolbar: { show: false } },
                    stroke: { curve: 'smooth', width: 3 },
                    dataLabels: { enabled: false },
                    series: [
                        { name: 'Yeni Kullanıcı', data: @json($trendUsers) },
                        { name: 'Senkron', data: @json($trendSyncs) },
                    ],
                    xaxis: { categories: @json($trendLabels) },
                    yaxis: { min: 0 },
                    colors: [colors.primary, colors.success],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.35,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    legend: { position: 'top' }
                });
                trendChart.render();
            }

            const cityElement = document.querySelector('#cityDistributionChart');
            if (cityElement) {
                const cityChart = new ApexCharts(cityElement, {
                    chart: { type: 'bar', height: 280, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                    dataLabels: { enabled: false },
                    series: [{ name: 'Kullanıcı', data: @json($cityValues) }],
                    xaxis: { categories: @json($cityLabels) },
                    colors: [colors.info],
                });
                cityChart.render();
            }

            const platformElement = document.querySelector('#platformDistributionChart');
            if (platformElement) {
                const labels = @json($platformLabels);
                const values = @json($platformValues);

                const platformChart = new ApexCharts(platformElement, {
                    chart: { type: 'donut', height: 280 },
                    labels: labels,
                    series: values.length > 0 ? values : [1],
                    colors: [colors.primary, colors.success, colors.warning, colors.info, colors.muted],
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: true },
                    noData: { text: 'Platform verisi bulunmuyor' }
                });
                platformChart.render();
            }

            const topZikirElement = document.querySelector('#topZikirChart');
            if (topZikirElement) {
                const labels = @json($topZikirLabels);
                const values = @json($topZikirValues);

                const topZikirChart = new ApexCharts(topZikirElement, {
                    chart: { type: 'bar', height: 340, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                    dataLabels: { enabled: false },
                    series: [{ name: 'Okunma Adedi', data: values }],
                    xaxis: { categories: labels },
                    colors: [colors.primary],
                    noData: { text: 'Henüz zikir okuma verisi bulunmuyor' }
                });
                topZikirChart.render();
            }

        })();
    </script>
@endpush
