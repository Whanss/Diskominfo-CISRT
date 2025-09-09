@csrf
<div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Ringkasan</label>
    <textarea name="summary" class="form-control" rows="3" maxlength="500" required>{{ old('summary', $event->summary ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Deskripsi (opsional)</label>
    <textarea name="description" class="form-control" rows="6">{{ old('description', $event->description ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mulai</label>
        <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at', isset($event->start_at) ? $event->start_at->format('Y-m-d\TH:i') : '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Selesai (opsional)</label>
        <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at', isset($event->end_at) ? $event->end_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Lokasi</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location ?? '') }}" required>
</div>
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" name="is_published" value="1" {{ old('is_published', ($event->is_published ?? false)) ? 'checked' : '' }}>
    <label class="form-check-label">Publikasi</label>
</div>
<div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
</div>