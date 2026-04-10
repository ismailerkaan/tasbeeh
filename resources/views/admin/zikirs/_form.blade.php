<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="zikir_category_id">Kategori</label>
            <select id="zikir_category_id" name="zikir_category_id" class="form-select @error('zikir_category_id') is-invalid @enderror" required>
                <option value="">Kategori seçin</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('zikir_category_id', $zikir->zikir_category_id ?? 0) === $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('zikir_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="zikir">Zikir</label>
            <input id="zikir" type="text" name="zikir" class="form-control @error('zikir') is-invalid @enderror" value="{{ old('zikir', $zikir->zikir ?? '') }}" maxlength="255" required>
            @error('zikir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="anlami">Anlamı</label>
            <textarea id="anlami" name="anlami" rows="3" class="form-control @error('anlami') is-invalid @enderror" required>{{ old('anlami', $zikir->anlami ?? '') }}</textarea>
            @error('anlami')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="fazileti">Fazileti</label>
            <textarea id="fazileti" name="fazileti" rows="3" class="form-control @error('fazileti') is-invalid @enderror" required>{{ old('fazileti', $zikir->fazileti ?? '') }}</textarea>
            @error('fazileti')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-2">
            <label class="form-label" for="hedef">Hedef</label>
            <input id="hedef" type="number" min="1" name="hedef" class="form-control @error('hedef') is-invalid @enderror" value="{{ old('hedef', $zikir->hedef ?? 33) }}" required>
            @error('hedef')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ isset($zikir) ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.zikirs.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
