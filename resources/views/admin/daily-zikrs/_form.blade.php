<div class="row">
    <div class="col-md-4">
        <div class="mb-1">
            <label class="form-label" for="date">Tarih</label>
            <input id="date" type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', isset($dailyZikr) ? $dailyZikr->date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-1">
            <label class="form-label" for="locale">Dil (opsiyonel)</label>
            <input id="locale" type="text" name="locale" class="form-control @error('locale') is-invalid @enderror" value="{{ old('locale', $dailyZikr->locale ?? 'tr') }}" maxlength="10" placeholder="tr">
            @error('locale')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-1">
            <label class="form-label d-block">Durum</label>
            <div class="form-check form-switch mt-50">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked((bool) old('is_active', $dailyZikr->is_active ?? true))>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
            @error('is_active')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="zikir_id">Zikir</label>
            <select id="zikir_id" name="zikir_id" class="form-select @error('zikir_id') is-invalid @enderror" required>
                <option value="">Zikir secin</option>
                @foreach ($zikirs as $zikir)
                    <option value="{{ $zikir->id }}" @selected((int) old('zikir_id', $dailyZikr->zikir_id ?? 0) === $zikir->id)>
                        {{ $zikir->zikir }}
                    </option>
                @endforeach
            </select>
            @error('zikir_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ isset($dailyZikr) ? 'Guncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.daily-zikrs.index') }}" class="btn btn-outline-secondary">Iptal</a>
    </div>
</div>
