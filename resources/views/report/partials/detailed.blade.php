@php
    $bookings = $bookings ?? collect();
@endphp

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tipe</th>
                        <th>Check-in</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_date->format('d M Y') }}</td>
                            <td><strong>{{ $booking->formatted_queue_number }}</strong></td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{!! $booking->category_badge !!}</td>
                            <td>{!! $booking->status_badge !!}</td>
                            <td>
                                @if($booking->booking_type == 'online')
                                    <span class="badge bg-info">Online</span>
                                @else
                                    <span class="badge bg-secondary">Walk-in</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->check_in_time)
                                    <small>{{ $booking->check_in_time->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->service_start_time)
                                    <small>{{ $booking->service_start_time->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->service_end_time)
                                    <small>{{ $booking->service_end_time->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->service_duration)
                                    <span class="badge bg-light text-dark">{{ $booking->service_duration }} min</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data booking untuk periode ini
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-3">
                {{ $bookings->appends(request()->query())->links() }}
            </div>

            <div class="text-muted small mt-2">
                Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} data
            </div>
        @endif
    </div>
</div>
