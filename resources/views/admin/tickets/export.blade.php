@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Export Laporan Tiket</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tickets.export') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="code_tracking">Code Tracking</label>
                                    <input type="text" name="code_tracking" class="form-control"
                                        placeholder="Masukkan Code Tracking" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger">Export ke
                                        PDF</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
