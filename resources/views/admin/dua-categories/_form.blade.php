@php($isEdit = isset($duaCategory))

<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="name">Kategori Adı</label>
            <input
                id="name"
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $duaCategory->name ?? '') }}"
                maxlength="255"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mb-2">
            <input
                id="is_active"
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                @checked(old('is_active', $duaCategory->is_active ?? true))
            >
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Güncelle' : 'Kaydet' }}
        </button>
        <a href="{{ route('admin.dua-categories.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
