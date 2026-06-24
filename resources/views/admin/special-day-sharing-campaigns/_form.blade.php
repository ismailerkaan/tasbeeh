<div class="row">
    <div class="col-md-8">
        <div class="mb-1">
            <label class="form-label" for="title">Başlık</label>
            <input id="title" name="title" type="text" maxlength="255" required class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $campaign->title ?? '') }}" placeholder="Örn. Kadir Geceniz Mübarek Olsun">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-1">
            <label class="form-label" for="publish_date">Gösterim Tarihi</label>
            <input id="publish_date" name="publish_date" type="date" required class="form-control @error('publish_date') is-invalid @enderror" value="{{ old('publish_date', isset($campaign) ? $campaign->publish_date->format('Y-m-d') : '') }}">
            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="message">Metin</label>
            <textarea id="message" name="message" rows="4" maxlength="2000" required class="form-control @error('message') is-invalid @enderror" placeholder="Kullanıcıya gösterilecek kısa mesajı yazın...">{{ old('message', $campaign->message ?? '') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-12">
        <div class="mb-2">
            <label class="form-label" for="images">Paylaşım Görselleri</label>
            <input id="images" name="images[]" type="file" multiple accept="image/jpeg,image/png,image/webp" {{ isset($campaign) ? '' : 'required' }} class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
            <div class="form-text">En fazla 10 adet JPG, PNG veya WebP. Görsel başına en fazla 15 MB.</div>
            @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    @if (isset($campaign) && $campaign->images->isNotEmpty())
        <div class="col-12"><div class="row mb-2">
            @foreach ($campaign->images as $image)
                <div class="col-md-3 mb-1"><div class="card border h-100">
                    <img src="{{ route('api.v1.special-day-sharing-images.show', $image) }}" alt="Paylaşım görseli" class="card-img-top" style="height:180px;object-fit:cover;">
                    <div class="card-body py-1"><div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}" id="remove_image_{{ $image->id }}">
                        <label class="form-check-label text-danger" for="remove_image_{{ $image->id }}">Görseli sil</label>
                    </div></div>
                </div></div>
            @endforeach
        </div></div>
    @endif

    <div class="col-12"><div class="form-check form-switch mb-2">
        <input id="is_active" name="is_active" type="checkbox" value="1" class="form-check-input" @checked(old('is_active', $campaign->is_active ?? true))>
        <label class="form-check-label" for="is_active">Yayın tarihinde uygulamada göster</label>
    </div></div>
    <div class="col-12 d-flex gap-1">
        <button type="submit" class="btn btn-primary">{{ isset($campaign) ? 'Güncelle' : 'Kaydet' }}</button>
        <a href="{{ route('admin.special-day-sharing-campaigns.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>
</div>
