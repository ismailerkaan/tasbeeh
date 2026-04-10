@php($isEdit = isset($mobileUser))

<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="external_user_id">
                <i data-feather="user" class="me-50"></i>Kullanıcı ID
            </label>
            <input
                id="external_user_id"
                type="text"
                name="external_user_id"
                class="form-control @error('external_user_id') is-invalid @enderror"
                value="{{ old('external_user_id', $mobileUser->external_user_id ?? '') }}"
                maxlength="255"
                required
            >
            @error('external_user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-1">
            <label class="form-label" for="city">
                <i data-feather="map-pin" class="me-50"></i>Şehir
            </label>
            <input
                id="city"
                type="text"
                name="city"
                class="form-control @error('city') is-invalid @enderror"
                value="{{ old('city', $mobileUser->city ?? '') }}"
                maxlength="255"
            >
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-1">
            <label class="form-label" for="district">
                <i data-feather="navigation" class="me-50"></i>İlçe
            </label>
            <input
                id="district"
                type="text"
                name="district"
                class="form-control @error('district') is-invalid @enderror"
                value="{{ old('district', $mobileUser->district ?? '') }}"
                maxlength="255"
            >
            @error('district')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-1">
            <label class="form-label" for="total_zikir_count">
                <i data-feather="hash" class="me-50"></i>Toplam Zikir Sayısı
            </label>
            <input
                id="total_zikir_count"
                type="number"
                min="0"
                step="1"
                name="total_zikir_count"
                class="form-control @error('total_zikir_count') is-invalid @enderror"
                value="{{ old('total_zikir_count', $mobileUser->total_zikir_count ?? 0) }}"
                required
            >
            @error('total_zikir_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-1">
            <label class="form-label" for="synced_at">
                <i data-feather="clock" class="me-50"></i>Son Senkron
            </label>
            <input
                id="synced_at"
                type="datetime-local"
                name="synced_at"
                class="form-control @error('synced_at') is-invalid @enderror"
                value="{{ old('synced_at', isset($mobileUser?->synced_at) ? $mobileUser->synced_at->format('Y-m-d\TH:i') : '') }}"
            >
            @error('synced_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-12 d-flex align-items-center">
        <div class="form-check form-switch mb-1">
            <input
                id="is_opt_in"
                type="checkbox"
                name="is_opt_in"
                value="1"
                class="form-check-input"
                @checked(old('is_opt_in', $mobileUser->is_opt_in ?? true))
            >
            <label class="form-check-label" for="is_opt_in">
                <i data-feather="bell" class="me-50"></i>Bildirim İzni Açık
            </label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Güncelle' : 'Kaydet' }}
        </button>
        <a href="{{ route('admin.mobile-users.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
