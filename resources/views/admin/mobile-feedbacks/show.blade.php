@extends('admin.Masterpage')

@section('title', 'Admin | Geri Bildirim Detayı')

@section('content')
    <section class="pb-1">
        @if (session('status'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">{{ session('status') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-25"><i data-feather="message-square" class="me-50"></i>Geri Bildirim Detayı</h3>
                    <p class="text-muted mb-0">#{{ $feedback->id }} - {{ $feedback->full_name }}</p>
                </div>
                <a href="{{ route('admin.mobile-feedbacks.index') }}" class="btn btn-outline-secondary">Listeye Dön</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Mesaj</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $feedback->message }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 mb-1">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Bilgiler</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1"><small class="text-muted d-block">Kullanıcı ID</small><strong>{{ $feedback->user_identifier ?: '-' }}</strong></div>
                        <div class="mb-1"><small class="text-muted d-block">Şehir / İlçe</small><strong>{{ $feedback->city ?: '-' }} / {{ $feedback->district ?: '-' }}</strong></div>
                        <div class="mb-1"><small class="text-muted d-block">Platform</small><strong>{{ strtoupper((string) $feedback->platform) ?: '-' }}</strong></div>
                        <div class="mb-1"><small class="text-muted d-block">Cihaz</small><strong>{{ $feedback->device_model ?: '-' }}</strong></div>
                        <div class="mb-1"><small class="text-muted d-block">OS Versiyonu</small><strong>{{ $feedback->os_version ?: '-' }}</strong></div>
                        <div class="mb-1"><small class="text-muted d-block">Token</small><small class="d-block">{{ \Illuminate\Support\Str::limit((string) $feedback->fcm_token, 60) ?: '-' }}</small></div>
                        <div class="mb-1"><small class="text-muted d-block">Tarih</small><strong>{{ $feedback->created_at?->format('d.m.Y H:i') ?: '-' }}</strong></div>
                        <hr>
                        <form action="{{ route('admin.mobile-feedbacks.update', $feedback) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-1">
                                <label for="status" class="form-label">Durum</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="{{ \App\Models\MobileFeedback::STATUS_NEW }}" @selected($feedback->status === \App\Models\MobileFeedback::STATUS_NEW)>Yeni</option>
                                    <option value="{{ \App\Models\MobileFeedback::STATUS_REVIEWED }}" @selected($feedback->status === \App\Models\MobileFeedback::STATUS_REVIEWED)>İncelendi</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Durumu Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
