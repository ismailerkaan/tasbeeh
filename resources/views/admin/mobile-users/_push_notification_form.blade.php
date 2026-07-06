<form action="{{ route('admin.mobile-users.push-notifications.store', $mobileUser) }}" method="POST">
    @csrf

    <div class="mb-1">
        <label class="form-label" for="push_title_{{ $mobileUser->id }}">Başlık</label>
        <input
            id="push_title_{{ $mobileUser->id }}"
            name="title"
            type="text"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title') }}"
            maxlength="255"
            required
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-1">
        <label class="form-label" for="push_body_{{ $mobileUser->id }}">Mesaj</label>
        <textarea
            id="push_body_{{ $mobileUser->id }}"
            name="body"
            rows="{{ $rows ?? 4 }}"
            class="form-control @error('body') is-invalid @enderror"
            required
        >{{ old('body') }}</textarea>
        @error('body')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-{{ $compact ?? false ? '1' : '2' }}">
        <label class="form-label" for="push_data_{{ $mobileUser->id }}">Ek Data (JSON)</label>
        <textarea
            id="push_data_{{ $mobileUser->id }}"
            name="data"
            rows="{{ $compact ?? false ? 2 : 3 }}"
            class="form-control @error('data') is-invalid @enderror"
            placeholder='{"screen":"home"}'
        >{{ old('data') }}</textarea>
        @error('data')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Hedef kullanıcı: {{ $mobileUser->external_user_id }}</small>
    </div>

    <button type="submit" class="btn btn-primary">
        <i data-feather="send" class="me-50"></i>Gönder
    </button>
</form>
