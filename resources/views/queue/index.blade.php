@extends('layouts.app')

@section('title', 'Kelola Antrian')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Antrian Real-time</h3>
                <p class="text-subtitle text-muted">Monitor dan kelola antrian pasien hari ini</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Antrian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <h6 class="text-muted font-semibold mb-0">Total Hari Ini</h6>
                    <h3 class="font-extrabold mb-0" id="stat-total">{{ $stats['total_today'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-warning bg-opacity-10">
                <div class="card-body px-3 py-4">
                    <h6 class="text-warning font-semibold mb-0">Menunggu</h6>
                    <h3 class="font-extrabold mb-0 text-warning" id="stat-waiting">{{ $stats['waiting'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-primary bg-opacity-10">
                <div class="card-body px-3 py-4">
                    <h6 class="text-primary font-semibold mb-0">Berlangsung</h6>
                    <h3 class="font-extrabold mb-0 text-primary" id="stat-serving">{{ $stats['serving'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-success bg-opacity-10">
                <div class="card-body px-3 py-4">
                    <h6 class="text-success font-semibold mb-0">Selesai</h6>
                    <h3 class="font-extrabold mb-0 text-success" id="stat-completed">{{ $stats['completed'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-success bg-opacity-10">
                <div class="card-body px-3 py-4">
                    <h6 class="text-success font-semibold mb-0">BPJS</h6>
                    <h3 class="font-extrabold mb-0 text-success">{{ $stats['bpjs_today'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-primary bg-opacity-10">
                <div class="card-body px-3 py-4">
                    <h6 class="text-primary font-semibold mb-0">Umum</h6>
                    <h3 class="font-extrabold mb-0 text-primary">{{ $stats['umum_today'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Serving -->
    <section class="section">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 text-white">
                    <i class="bi bi-person-fill-check"></i> Sedang Dilayani
                </h4>
            </div>
            <div class="card-body" id="serving-section">
                @if($servingNow)
                <div class="row align-items-center py-3">
                    <div class="col-12 col-md-3 text-center mb-3 mb-md-0">
                        <div class="bg-primary bg-opacity-10 rounded p-4">
                            <p class="text-muted mb-1">Nomor Antrian</p>
                            <h1 class="display-1 fw-bold text-primary mb-0">{{ $servingNow->formatted_queue_number }}</h1>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="35%"><strong>Nama Pasien</strong></td>
                                <td>: {{ $servingNow->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>: {!! $servingNow->category_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Mulai Dilayani</strong></td>
                                <td>: {{ $servingNow->service_start_time->format('H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td><strong>Durasi</strong></td>
                                <td>: <span id="service-duration">{{ $servingNow->service_start_time->diffForHumans(null, true) }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-3 text-center">
                        <form action="{{ route('booking.finish-service', $servingNow) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 mb-2" onclick="return confirm('Selesaikan pelayanan untuk {{ $servingNow->user->name }}?')">
                                <i class="bi bi-check-circle"></i> Selesai
                            </button>
                        </form>
                        <a href="{{ route('booking.show', $servingNow) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-person-x text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Tidak ada pasien yang sedang dilayani</p>
                    @if($nextQueue)
                    <form action="{{ route('queue.call-next') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="button" class="btn btn-primary btn-lg" onclick="confirmCallNext()">
                            <i class="bi bi-megaphone"></i> Panggil Pasien Berikutnya ({{ $nextQueue->formatted_queue_number }})
                        </button>
                    </form>
                    <script>
                        function confirmCallNext() {
                            Swal.fire({
                                title: 'Panggil Pasien Berikutnya?',
                                html: 'Nomor Antrian: <strong>{{ $nextQueue->formatted_queue_number }}</strong><br>Nama: {{ $nextQueue->user->name }}',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#0d6efd',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya, Panggil!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.querySelector('form[action="{{ route('queue.call-next') }}"]').submit();
                                }
                            });
                        }
                    </script>
                    @else
                    <p class="text-muted">Tidak ada antrian</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Waiting Queue -->
    <section class="section">
        <div class="card">
            <div class="card-header bg-warning bg-opacity-10">
                <h4 class="card-title mb-0 text-warning">
                    <i class="bi bi-hourglass-split"></i> Antrian Menunggu ({{ $waitingQueue->count() }})
                </h4>
            </div>
            <div class="card-body">
                @if($waitingQueue->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="15%">No. Antrian</th>
                                <th>Pasien</th>
                                <th>Kategori</th>
                                <th>Check-in</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waitingQueue as $queue)
                            <tr>
                                <td>
                                    <h3 class="mb-0 text-warning">{{ $queue->formatted_queue_number }}</h3>
                                </td>
                                <td>
                                    <strong>{{ $queue->user->name }}</strong>
                                    <br><small class="text-muted">{{ $queue->user->phone ?? '-' }}</small>
                                </td>
                                <td>{!! $queue->category_badge !!}</td>
                                <td>
                                    <small>{{ $queue->check_in_time->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                onclick="callPatient{{ $queue->id }}()"
                                                {{ $servingNow ? 'disabled' : '' }}>
                                            <i class="bi bi-megaphone"></i> Panggil
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-warning"
                                                onclick="skipPatient{{ $queue->id }}()">
                                            <i class="bi bi-skip-forward"></i> Lewati
                                        </button>
                                        <a href="{{ route('booking.show', $queue) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <!-- Call Form -->
                                    <form id="call-form-{{ $queue->id }}"
                                          action="{{ route('queue.call-specific', $queue) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>

                                    <!-- Skip Form -->
                                    <form id="skip-form-{{ $queue->id }}"
                                          action="{{ route('queue.skip', $queue) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        <input type="hidden" name="skip_reason" value="Belum hadir">
                                    </form>

                                    <script>
                                        function callPatient{{ $queue->id }}() {
                                            Swal.fire({
                                                title: 'Panggil Pasien?',
                                                html: '<strong>{{ $queue->user->name }}</strong><br>Nomor: {{ $queue->formatted_queue_number }}',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#0d6efd',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Panggil!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('call-form-{{ $queue->id }}').submit();
                                                }
                                            });
                                        }

                                        function skipPatient{{ $queue->id }}() {
                                            Swal.fire({
                                                title: 'Lewati Pasien?',
                                                html: '<strong>{{ $queue->user->name }}</strong><br>Nomor: {{ $queue->formatted_queue_number }}',
                                                icon: 'warning',
                                                input: 'text',
                                                inputPlaceholder: 'Alasan (optional)',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ffc107',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Lewati',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    const form = document.getElementById('skip-form-{{ $queue->id }}');
                                                    form.querySelector('input[name="skip_reason"]').value = result.value || 'Belum hadir';
                                                    form.submit();
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada antrian menunggu
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Additional Sections in Collapsible -->
    <div class="row">
        <!-- Not Checked In -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header bg-secondary bg-opacity-10">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock"></i> Belum Check-in ({{ $notCheckedIn->count() }})
                    </h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if($notCheckedIn->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($notCheckedIn as $booking)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $booking->formatted_queue_number }}</strong> - {{ $booking->user->name }}
                                    <br><small class="text-muted">{!! $booking->category_badge !!}</small>
                                </div>
                                <a href="{{ route('booking.show', $booking) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted small mb-0">Semua sudah check-in</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header bg-success bg-opacity-10">
                    <h6 class="card-title mb-0 text-success">
                        <i class="bi bi-check-circle"></i> Selesai ({{ $completed->count() }})
                    </h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if($completed->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($completed as $booking)
                        <li class="list-group-item px-0">
                            <strong>{{ $booking->formatted_queue_number }}</strong> - {{ $booking->user->name }}
                            <br><small class="text-muted">Selesai: {{ $booking->service_end_time->format('H:i') }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted small mb-0">Belum ada yang selesai</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cancelled Today -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header bg-danger bg-opacity-10">
                    <h6 class="card-title mb-0 text-danger">
                        <i class="bi bi-x-circle"></i> Dibatalkan ({{ $cancelled->count() }})
                    </h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if($cancelled->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($cancelled as $booking)
                        <li class="list-group-item px-0">
                            <strong>{{ $booking->formatted_queue_number }}</strong> - {{ $booking->user->name }}
                            <br><small class="text-muted">{{ $booking->cancellation_reason }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted small mb-0">Tidak ada pembatalan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Auto-refresh every 30 seconds --}}
<script>
    // Auto-refresh page data every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);

    // Update service duration every minute
    @if($servingNow)
    setInterval(function() {
        const startTime = new Date('{{ $servingNow->service_start_time }}');
        const now = new Date();
        const diff = Math.floor((now - startTime) / 1000 / 60);
        document.getElementById('service-duration').textContent = diff + ' menit';
    }, 60000);
    @endif
</script>
@endsection
