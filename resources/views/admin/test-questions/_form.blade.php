@php($isEdit = isset($testQuestion))
@php($storedOptions = old('options', $testQuestion->options ?? []))
@php($optionKeys = ['A', 'B', 'C', 'D', 'E'])

<div class="row">
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="test_level_id">Seviye</label>
            <select id="test_level_id" name="test_level_id" class="form-select @error('test_level_id') is-invalid @enderror" required>
                <option value="">Seviye seçin</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}" @selected((int) old('test_level_id', $testQuestion->test_level_id ?? 0) === $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
            @error('test_level_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="question">Soru</label>
            <textarea id="question" name="question" rows="4" class="form-control @error('question') is-invalid @enderror" required>{{ old('question', $testQuestion->question ?? '') }}</textarea>
            @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Şıklar</label>
        <div class="row">
            @foreach ($optionKeys as $index => $key)
                <div class="col-md-6">
                    <div class="mb-1">
                        <div class="input-group">
                            <span class="input-group-text">{{ $key }}</span>
                            <input type="text" name="options[]" class="form-control @error('options.'.$index) is-invalid @enderror" value="{{ $storedOptions[$index] ?? '' }}" {{ $index < 2 ? 'required' : '' }}>
                            @error('options.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @error('options')<div class="text-danger small mb-1">{{ $message }}</div>@enderror
        <div class="form-text mb-1">En az iki şık girin. Boş bırakılan son şıklar kaydedilmez.</div>
    </div>

    <div class="col-md-6">
        <div class="mb-1">
            <label class="form-label" for="correct_option_key">Doğru Şık</label>
            <select id="correct_option_key" name="correct_option_key" class="form-select @error('correct_option_key') is-invalid @enderror" required>
                @foreach ($optionKeys as $key)
                    <option value="{{ $key }}" @selected(old('correct_option_key', $testQuestion->correct_option_key ?? 'A') === $key)>{{ $key }}</option>
                @endforeach
            </select>
            @error('correct_option_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-1">
            <label class="form-label" for="sort_order">Sıralama</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="65535" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $testQuestion->sort_order ?? 0) }}" required>
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="explanation">Cevap Açıklaması</label>
            <textarea id="explanation" name="explanation" rows="3" class="form-control @error('explanation') is-invalid @enderror">{{ old('explanation', $testQuestion->explanation ?? '') }}</textarea>
            @error('explanation')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mb-2">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $testQuestion->is_active ?? true))>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.test-questions.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>