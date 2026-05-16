<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="hadis_category_id">Kategori</label>
            <select id="hadis_category_id" name="hadis_category_id" class="form-select @error('hadis_category_id') is-invalid @enderror" required>
                <option value="">Kategori seçin</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('hadis_category_id', $hadis->hadis_category_id ?? 0) === $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('hadis_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="source">Kaynağı</label>
            <input id="source" type="text" name="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source', $hadis->source ?? '') }}" maxlength="255" required>
            @error('source')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="hadis">Hadis</label>
            <textarea id="hadis" name="hadis" rows="4" class="form-control @error('hadis') is-invalid @enderror" required>{{ old('hadis', $hadis->hadis ?? '') }}</textarea>
            @error('hadis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="turkce_meali">Türkçe Meali</label>
            <textarea id="turkce_meali" name="turkce_meali" rows="4" class="form-control @error('turkce_meali') is-invalid @enderror" required>{{ old('turkce_meali', $hadis->turkce_meali ?? '') }}</textarea>
            @error('turkce_meali')
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
                @checked(old('is_active', $hadis->is_active ?? true))
            >
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ isset($hadis) ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.hadises.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
