@extends('layouts.app')

@section('title', 'Laporan & Statistik')
@section('page-title', 'Laporan & Statistik')

@section('content')
<section class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Filter Laporan</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('report.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="{{ $start_date }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="{{ $end_date }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Semua</option>
                            <option value="bpjs" {{ $category == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                            <option value="umum" {{ $category == 'umum' ? 'selected' : '' }}>Umum</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua</option>
                            <option value="booking" {{ $status == 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="berlangsung" {{ $status == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" name="tab" value="{{ $active_tab }}">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                </form>

                @can('report.export')
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <a href="{{ route('report.export.excel', request()->query()) }}"
                               class="btn btn-success btn-sm">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel/CSV
                            </a>
                            <a href="{{ route('report.export.pdf', request()->query()) }}"
                               class="btn btn-danger btn-sm" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <form method="GET" action="{{ route('report.index') }}" class="d-inline">
                            @foreach(request()->except('tab') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <button type="submit" name="tab" value="summary"
                                    class="nav-link {{ $active_tab == 'summary' ? 'active' : '' }}">
                                <i class="bi bi-bar-chart"></i> Summary
                            </button>
                        </form>
                    </li>
                    <li class="nav-item" role="presentation">
                        <form method="GET" action="{{ route('report.index') }}" class="d-inline">
                            @foreach(request()->except('tab') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <button type="submit" name="tab" value="detailed"
                                    class="nav-link {{ $active_tab == 'detailed' ? 'active' : '' }}">
                                <i class="bi bi-table"></i> Detail
                            </button>
                        </form>
                    </li>
                    <li class="nav-item" role="presentation">
                        <form method="GET" action="{{ route('report.index') }}" class="d-inline">
                            @foreach(request()->except('tab') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <button type="submit" name="tab" value="performance"
                                    class="nav-link {{ $active_tab == 'performance' ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Performance
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    @if($active_tab == 'summary')
                        @include('report.partials.summary')
                    @elseif($active_tab == 'detailed')
                        @include('report.partials.detailed')
                    @elseif($active_tab == 'performance')
                        @include('report.partials.performance')
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .nav-link {
        border: none;
        background: none;
        cursor: pointer;
        padding: 0.5rem 1rem;
    }
    .nav-link.active {
        border-bottom: 2px solid #435ebe;
        color: #435ebe;
    }
    .stat-card {
        border-left: 4px solid;
    }
    .stat-card.primary {
        border-color: #435ebe;
    }
    .stat-card.success {
        border-color: #198754;
    }
    .stat-card.danger {
        border-color: #dc3545;
    }
    .stat-card.warning {
        border-color: #ffc107;
    }
    .stat-card.info {
        border-color: #0dcaf0;
    }
</style>
@endpush
