<div class="mb-1">
    <label class="form-label" for="title">Başlık</label>
    <input
        id="title"
        name="title"
        type="text"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', ($pushNotification ?? null)?->title) }}"
        maxlength="255"
        required
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-1">
    <label class="form-label" for="body">Mesaj</label>
    <textarea id="body" name="body" rows="4" class="form-control @error('body') is-invalid @enderror" required>{{ old('body', ($pushNotification ?? null)?->body) }}</textarea>
    @error('body')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-1">
    <label class="form-label" for="target_type">Hedef</label>
    <select id="target_type" name="target_type" class="form-select @error('target_type') is-invalid @enderror" required>
        <option value="all" @selected(old('target_type', ($pushNotification ?? null)?->target_type ?? 'all') === 'all')>Tüm Kullanıcılar</option>
        <option value="user" @selected(old('target_type', ($pushNotification ?? null)?->target_type) === 'user')>Belirli Kullanıcı</option>
    </select>
    @error('target_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-1">
    <label class="form-label" for="target_user_identifier">Kullanıcı Kimliği</label>
    <input
        id="target_user_identifier"
        name="target_user_identifier"
        type="text"
        class="form-control @error('target_user_identifier') is-invalid @enderror"
        value="{{ old('target_user_identifier', ($pushNotification ?? null)?->target_user_identifier) }}"
        placeholder="Örn: user_42"
    >
    <small class="text-muted">Sadece "Belirli Kullanıcı" seçildiğinde zorunludur.</small>
    @error('target_user_identifier')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-2">
    <label class="form-label" for="data">Ek Data (JSON)</label>
    <textarea id="data" name="data" rows="4" class="form-control @error('data') is-invalid @enderror" placeholder='{"screen":"dua_detail","id":"123"}'>{{ old('data', isset($pushNotification) && is_array($pushNotification->data) ? json_encode($pushNotification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
    @error('data')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
