@php($isEdit = isset($testLevel))

<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="test_category_id">Kategori</label>
            <select id="test_category_id" name="test_category_id" class="form-select @error('test_category_id') is-invalid @enderror">
                <option value="">Kategori seçin</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('test_category_id', $testLevel->test_category_id ?? 0) === $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Örn: Namaz ile alakalı sorular. Seviye bu kategorinin altında görünür.</div>
            @error('test_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="name">Seviye Adı</label>
            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $testLevel->name ?? '') }}" maxlength="255" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="description">Açıklama</label>
            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $testLevel->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="sort_order">Sıralama</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $testLevel->sort_order ?? 0) }}" required>
            <div class="form-text">Küçük sayı uygulamada daha önce gösterilir.</div>
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mb-2">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $testLevel->is_active ?? true))>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.test-levels.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>