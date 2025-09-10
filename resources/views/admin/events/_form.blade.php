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

        <input type="datetime-local" name="start_at" class="form-control"
            value="{{ old('start_at', isset($event->start_at) ? $event->start_at->format('Y-m-d\TH:i') : '') }}"
            required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label d-flex align-items-center gap-1">Selesai</label>
        @php
            $endModeOld = old('end_mode');
            $hasEndVal = old('end_at', isset($event->end_at) ? $event->end_at->format('Y-m-d\\TH:i') : '') !== '';
            $endMode = $endModeOld ?? ($hasEndVal ? 'date' : 'open');
        @endphp
        <div id="end_at_group" class="input-group">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <input type="datetime-local" name="end_at" id="end_at_input" class="form-control"
                value="{{ old('end_at', isset($event->end_at) ? $event->end_at->format('Y-m-d\\TH:i') : '') }}">
        </div>
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="form-check form-check-inline m-0">
                <input class="form-check-input" type="radio" name="end_mode" id="end_mode_date" value="date"
                    {{ $endMode === 'date' ? 'checked' : '' }}>
                <label class="form-check-label" for="end_mode_date">Atur tanggal selesai</label>
            </div>
            <div class="form-check form-check-inline m-0">
                <input class="form-check-input" type="radio" name="end_mode" id="end_mode_open" value="open"
                    {{ $endMode === 'open' ? 'checked' : '' }}>
                <label class="form-check-label" for="end_mode_open">Sampai Selesai</label>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const endModeDate = document.getElementById('end_mode_date');
            const endModeOpen = document.getElementById('end_mode_open');
            const endAtInput = document.getElementById('end_at_input');
            const endAtGroup = document.getElementById('end_at_group');

            function syncEndMode() {
                const isDate = endModeDate && endModeDate.checked;
                if (endAtInput) {
                    endAtInput.disabled = !isDate;
                    endAtInput.required = !!isDate;
                }
                if (endAtGroup) {
                    endAtGroup.style.display = isDate ? '' : 'none';
                }
            }
            endModeDate && endModeDate.addEventListener('change', syncEndMode);
            endModeOpen && endModeOpen.addEventListener('change', syncEndMode);
            syncEndMode();
        });
    </script>
</div>
<div class="mb-3">
    <label class="form-label">Lokasi</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location ?? '') }}"
        required>
</div>
<div class="mb-3">
    <label class="form-label">Gambar (opsional)</label>
    <input type="file" name="image" class="form-control" accept="image/*">
    @if (!empty($event?->image))
        <div class="mt-2">
            <small class="text-muted d-block mb-1">Gambar saat ini:</small>
            <img src="{{ asset($event->image) }}" alt="Current Image" style="max-width: 220px; height: auto;"
                class="border rounded">
        </div>
    @endif
</div>
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" name="is_published" value="1"
        {{ old('is_published', $event->is_published ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Publikasi</label>
</div>
<div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
</div>
