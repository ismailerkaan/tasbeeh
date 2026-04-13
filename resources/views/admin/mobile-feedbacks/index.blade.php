@extends('admin.Masterpage')

@section('title', 'Admin | Geri Bildirimler')

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
                        <h4 class="card-title mb-0">Geri Bildirimler</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ad Soyad</th>
                                        <th>Mesaj</th>
                                        <th>Konum</th>
                                        <th>Platform</th>
                                        <th>Durum</th>
                                        <th>Tarih</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->id }}</td>
                                            <td>{{ $feedback->full_name }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($feedback->message, 70) }}</td>
                                            <td>{{ $feedback->city ?: '-' }} / {{ $feedback->district ?: '-' }}</td>
                                            <td>{{ strtoupper((string) $feedback->platform) ?: '-' }}</td>
                                            <td>
                                                @if ($feedback->status === \App\Models\MobileFeedback::STATUS_REVIEWED)
                                                    <span class="badge bg-light-success text-success">İncelendi</span>
                                                @else
                                                    <span class="badge bg-light-warning text-warning">Yeni</span>
                                                @endif
                                            </td>
                                            <td>{{ $feedback->created_at?->format('d.m.Y H:i') ?: '-' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.mobile-feedbacks.show', $feedback) }}" class="btn btn-sm btn-outline-primary">Detay</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Henüz geri bildirim yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $feedbacks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
