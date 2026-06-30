<tr>
    <td>
        <h4 class="mb-0 text-primary">{{ $booking->formatted_queue_number }}</h4>
    </td>
    <td>
        <strong>{{ $booking->user->name }}</strong>
        <br><small class="text-muted">{{ $booking->user->phone ?? '-' }}</small>
    </td>
    <td>
        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
        <br><small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l') }}</small>
    </td>
    <td>{!! $booking->category_badge !!}</td>
    <td>{!! $booking->status_badge !!}</td>
    <td>
        <span class="badge {{ $booking->booking_type === 'online' ? 'bg-info' : 'bg-warning' }}">
            {{ ucfirst($booking->booking_type) }}
        </span>
    </td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('booking.show', $booking) }}"
               class="btn btn-sm btn-info"
               title="Detail">
                <i class="bi bi-eye"></i>
            </a>

            @if($booking->patient_category === 'bpjs' && in_array($booking->status, ['booking', 'menunggu', 'berlangsung']))
            <button type="button"
                    class="btn btn-sm btn-outline-success"
                    onclick="editQueue{{ $booking->id }}()"
                    title="Ubah Nomor Antrian (sinkron Mobile JKN)">
                <i class="bi bi-pencil-square"></i>
            </button>
            @endif

            @if($booking->status === 'booking')
            <button type="button"
                    class="btn btn-sm btn-success"
                    onclick="checkIn{{ $booking->id }}()"
                    title="Check-in">
                <i class="bi bi-check-circle"></i>
            </button>
            @endif

            @if($booking->status === 'menunggu')
            <button type="button"
                    class="btn btn-sm btn-primary"
                    onclick="startService{{ $booking->id }}()"
                    title="Mulai Pelayanan">
                <i class="bi bi-play-circle"></i>
            </button>
            @endif

            @if($booking->status === 'berlangsung')
            <button type="button"
                    class="btn btn-sm btn-success"
                    onclick="finishService{{ $booking->id }}()"
                    title="Selesai">
                <i class="bi bi-check2-circle"></i>
            </button>
            @endif

            @if(in_array($booking->status, ['booking', 'menunggu']))
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="cancelBooking{{ $booking->id }}()"
                    title="Batalkan">
                <i class="bi bi-x-circle"></i>
            </button>
            @endif
        </div>

        <!-- Hidden Forms -->
        @if($booking->patient_category === 'bpjs' && in_array($booking->status, ['booking', 'menunggu', 'berlangsung']))
        <form id="queue-form-{{ $booking->id }}"
              action="{{ route('booking.update-queue-number', $booking) }}"
              method="POST" class="d-none">
            @csrf
            <input type="hidden" name="queue_number" value="{{ $booking->queue_number }}">
        </form>
        <script>
            function editQueue{{ $booking->id }}() {
                Swal.fire({
                    title: 'Ubah Nomor Antrian BPJS',
                    html: '<strong>{{ $booking->user->name }}</strong><br>Nomor saat ini: {{ $booking->formatted_queue_number }}<br><small class="text-muted">Sesuaikan dengan nomor di Mobile JKN.</small>',
                    icon: 'info',
                    input: 'number',
                    inputValue: '{{ $booking->queue_number }}',
                    inputAttributes: { min: 1, max: 999 },
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value || value < 1) {
                            return 'Masukkan nomor antrian yang valid';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('queue-form-{{ $booking->id }}');
                        form.querySelector('input[name="queue_number"]').value = result.value;
                        form.submit();
                    }
                });
            }
        </script>
        @endif

        @if($booking->status === 'booking')
        <form id="checkin-form-{{ $booking->id }}"
              action="{{ route('booking.check-in', $booking) }}"
              method="POST" class="d-none">
            @csrf
        </form>
        <script>
            function checkIn{{ $booking->id }}() {
                Swal.fire({
                    title: 'Check-in Pasien?',
                    html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Check-in!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkin-form-{{ $booking->id }}').submit();
                    }
                });
            }
        </script>
        @endif

        @if($booking->status === 'menunggu')
        <form id="start-form-{{ $booking->id }}"
              action="{{ route('booking.start-service', $booking) }}"
              method="POST" class="d-none">
            @csrf
        </form>
        <script>
            function startService{{ $booking->id }}() {
                Swal.fire({
                    title: 'Mulai Pelayanan?',
                    html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Mulai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('start-form-{{ $booking->id }}').submit();
                    }
                });
            }
        </script>
        @endif

        @if($booking->status === 'berlangsung')
        <form id="finish-form-{{ $booking->id }}"
              action="{{ route('booking.finish-service', $booking) }}"
              method="POST" class="d-none">
            @csrf
        </form>
        <script>
            function finishService{{ $booking->id }}() {
                Swal.fire({
                    title: 'Selesai Pelayanan?',
                    html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('finish-form-{{ $booking->id }}').submit();
                    }
                });
            }
        </script>
        @endif

        @if(in_array($booking->status, ['booking', 'menunggu']))
        <form id="cancel-form-{{ $booking->id }}"
              action="{{ route('booking.cancel', $booking) }}"
              method="POST" class="d-none">
            @csrf
            <input type="hidden" name="cancellation_reason" value="Dibatalkan oleh admin">
        </form>
        <script>
            function cancelBooking{{ $booking->id }}() {
                Swal.fire({
                    title: 'Batalkan Booking?',
                    html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('cancel-form-{{ $booking->id }}').submit();
                    }
                });
            }
        </script>
        @endif
    </td>
</tr>
