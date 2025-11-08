<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Klinik - Display</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .main-display {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 20px;
        }

        .current-serving {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 3rem;
            border-radius: 30px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .queue-number-display {
            font-size: 12rem;
            font-weight: 900;
            color: #667eea;
            line-height: 1;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .patient-name-display {
            font-size: 3rem;
            font-weight: 600;
            margin-top: 1rem;
            color: #333;
        }

        .upcoming-queue {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
        }

        .queue-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem 1.5rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .queue-item .queue-num {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .queue-item .patient-name {
            font-size: 1.5rem;
            font-weight: 500;
        }

        .blink {
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.3; }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .current-time {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .no-queue {
            text-align: center;
            padding: 5rem 2rem;
            font-size: 2rem;
            opacity: 0.7;
        }

        .badge-category {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
        }
    </style>

    <!-- Auto-refresh every 10 seconds -->
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <div class="main-display">
        <!-- Header -->
        <div class="header">
            <h1 class="mb-2">
                <i class="bi bi-hospital"></i> KLINIK ANTRIAN
            </h1>
            <p class="mb-0 current-time" id="current-time"></p>
        </div>

        <!-- Currently Serving -->
        @if($servingNow)
        <div class="current-serving pulse">
            <div class="mb-3">
                <i class="bi bi-megaphone-fill text-primary" style="font-size: 4rem;"></i>
            </div>
            <h2 class="text-uppercase fw-bold mb-4">SEDANG DILAYANI</h2>
            <div class="queue-number-display blink">{{ $servingNow->formatted_queue_number }}</div>
            <div class="patient-name-display">{{ $servingNow->user->name }}</div>
            <div class="mt-3">
                @if($servingNow->patient_category === 'bpjs')
                    <span class="badge bg-success badge-category">BPJS</span>
                @else
                    <span class="badge bg-primary badge-category">UMUM</span>
                @endif
            </div>
        </div>
        @else
        <div class="current-serving">
            <i class="bi bi-hourglass-split text-muted" style="font-size: 5rem;"></i>
            <h2 class="text-muted mt-3">Tidak Ada Pasien yang Sedang Dilayani</h2>
        </div>
        @endif

        <!-- Upcoming Queue -->
        <div class="upcoming-queue">
            <h3 class="mb-4">
                <i class="bi bi-list-ol"></i> Antrian Berikutnya
            </h3>

            @if($upcomingQueue->count() > 0)
                @foreach($upcomingQueue as $queue)
                <div class="queue-item">
                    <div class="d-flex align-items-center">
                        <div class="queue-num me-4">{{ $queue->formatted_queue_number }}</div>
                        <div>
                            <div class="patient-name">{{ $queue->user->name }}</div>
                            <div class="mt-1">
                                @if($queue->patient_category === 'bpjs')
                                    <span class="badge bg-success">BPJS</span>
                                @else
                                    <span class="badge bg-primary">UMUM</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($loop->first)
                            <i class="bi bi-arrow-right-circle-fill" style="font-size: 2rem;"></i>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-queue">
                    <i class="bi bi-inbox"></i>
                    <p class="mt-3">Tidak ada antrian</p>
                </div>
            @endif
        </div>

        <!-- Recently Completed (Optional) -->
        @if($recentlyCompleted->count() > 0)
        <div class="mt-3 text-center opacity-75">
            <small>Terakhir Selesai:
                @foreach($recentlyCompleted as $completed)
                    {{ $completed->formatted_queue_number }}@if(!$loop->last), @endif
                @endforeach
            </small>
        </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const day = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const timeString = `${day}, ${date} ${month} ${year} - ${hours}:${minutes}:${seconds} WIB`;
            document.getElementById('current-time').textContent = timeString;
        }

        // Update clock every second
        updateClock();
        setInterval(updateClock, 1000);

        // Play sound when page loads (optional - for calling attention)
        window.addEventListener('load', function() {
            // You can add a notification sound here if needed
            console.log('Queue display loaded');
        });
    </script>
</body>
</html>
