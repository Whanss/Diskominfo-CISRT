@extends('layouts.admin')

@section('title', 'Detail Event')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Event</h3>
                    <div>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Judul</dt>
                        <dd class="col-sm-9">{{ $event->title }}</dd>

                        <dt class="col-sm-3">Ringkasan</dt>
                        <dd class="col-sm-9">{{ $event->summary }}</dd>

                        <dt class="col-sm-3">Waktu</dt>
                        <dd class="col-sm-9">
                            {{ optional($event->start_at)->format('d/m/Y H:i') }}
                            @if($event->end_at)
                                - {{ optional($event->end_at)->format('d/m/Y H:i') }}
                            @endif
                        </dd>

                        <dt class="col-sm-3">Lokasi</dt>
                        <dd class="col-sm-9">{{ $event->location }}</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{ $event->is_published ? 'Dipublikasi' : 'Draft' }}</dd>
                    </dl>

                    @if($event->description)
                        <hr>
                        <h5>Deskripsi</h5>
                        <div>{!! nl2br(e($event->description)) !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection