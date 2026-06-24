@php
    $recommendationsValue = old('recommendations_text');
    if ($recommendationsValue === null && isset($specialDay)) {
        $recommendationsValue = implode("\n", $specialDay->recommendations ?? []);
    }
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="mb-1">
            <label class="form-label" for="title">Günün Adı</label>
            <input id="title" name="title" type="text" maxlength="255" required
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $specialDay->title ?? '') }}"
                   placeholder="Örn. Kadir Gecesi">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-1">
            <label class="form-label" for="category">Kategori</label>
            <select id="category" name="category" required class="form-select @error('category') is-invalid @enderror">
                <option value="">Kategori seçin</option>
                @foreach ($categories as $key => $category)
                    <option value="{{ $key }}" @selected(old('category', $specialDay->category ?? '') === $key)>{{ $category['label'] }}</option>
                @endforeach
            </select>
            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-1">
            <label class="form-label" for="event_date">Tarih</label>
            <input id="event_date" name="event_date" type="date" required
                   class="form-control @error('event_date') is-invalid @enderror"
                   value="{{ old('event_date', isset($specialDay) ? $specialDay->event_date?->format('Y-m-d') : '') }}">
            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="hijri_date">Hicri Tarih <span class="text-muted">(Opsiyonel)</span></label>
            <input id="hijri_date" name="hijri_date" type="text" maxlength="100"
                   class="form-control @error('hijri_date') is-invalid @enderror"
                   value="{{ old('hijri_date', $specialDay->hijri_date ?? '') }}"
                   placeholder="Örn. 27 Ramazan 1447">
            @error('hijri_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="short_description">Kısa Açıklama <span class="text-muted">(Opsiyonel)</span></label>
            <input id="short_description" name="short_description" type="text" maxlength="500"
                   class="form-control @error('short_description') is-invalid @enderror"
                   value="{{ old('short_description', $specialDay->short_description ?? '') }}"
                   placeholder="Liste ekranında gösterilecek kısa bilgi">
            @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="description">Günün Anlamı ve Önemi</label>
            <textarea id="description" name="description" rows="6" required
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Bu özel günün anlamını ve önemini açıklayın...">{{ old('description', $specialDay->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="recommendations_text">O Gün Yapılması Önerilenler</label>
            <textarea id="recommendations_text" name="recommendations_text" rows="8" required
                      class="form-control @error('recommendations_text') is-invalid @enderror"
                      placeholder="Her öneriyi ayrı bir satıra yazın.&#10;Kur'an-ı Kerim okumak&#10;Dua ve istiğfar etmek&#10;İhtiyaç sahiplerine yardım etmek">{{ $recommendationsValue }}</textarea>
            <div class="form-text">Her satır mobil uygulamada ayrı bir madde olarak gösterilir.</div>
            @error('recommendations_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mb-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" class="form-check-input"
                   @checked(old('is_active', $specialDay->is_active ?? true))>
            <label class="form-check-label" for="is_active">Mobil uygulamada göster</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ isset($specialDay) ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.religious-special-days.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
