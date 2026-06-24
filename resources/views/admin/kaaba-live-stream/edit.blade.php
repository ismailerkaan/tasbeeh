@extends('admin.Masterpage')

@section('title', 'Admin | Kâbe Canlı Yayını')

@section('content')
    <section>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <div class="alert-body">{{ session('status') }}</div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div>
                    <h4 class="card-title mb-50">Kâbe Canlı Yayını</h4>
                    <p class="text-muted mb-0">Mobil uygulamada gösterilecek YouTube canlı yayın bağlantısını yönetin.</p>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kaaba-live-stream.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-1">
                        <label class="form-label" for="title">Başlık</label>
                        <input id="title" name="title" type="text" maxlength="100" required
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $liveStream?->title ?? 'Kâbe Canlı Yayını') }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="youtube_url">YouTube Canlı Yayın Linki</label>
                        <input id="youtube_url" name="youtube_url" type="url" required
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url', $liveStream?->youtube_url) }}">
                        <div class="form-text">youtube.com/watch, youtube.com/live, youtu.be ve embed bağlantıları desteklenir.</div>
                        @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="form-check-input"
                               @checked(old('is_active', $liveStream?->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Mobil uygulamada göster</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </section>
@endsection
