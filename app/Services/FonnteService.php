<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(?string $phone, string $message): bool
    {
        $token = config("services.fonnte.token");
        $target = $this->normalizePhone($phone);

        if (!$token || !$target) {
            Log::warning("Fonnte send skipped", [
                "has_token" => (bool) $token,
                "phone" => $phone,
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                "Authorization" => $token,
            ])
                ->asForm()
                ->timeout(10)
                ->post(config("services.fonnte.endpoint"), [
                    "target" => $target,
                    "message" => $message,
                    "countryCode" => "62",
                ]);

            if (!$response->successful()) {
                Log::error("Fonnte send failed", [
                    "target" => $target,
                    "status" => $response->status(),
                    "body" => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error("Fonnte send threw", [
                "target" => $target,
                "error" => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function bookingConfirmation(Booking $booking): bool
    {
        $clinic = config("services.fonnte.clinic_name");
        $date = Carbon::parse($booking->booking_date)
            ->locale("id")
            ->isoFormat("dddd, D MMMM Y");
        $category = strtoupper($booking->patient_category);

        $message = "Halo {$booking->user->name},\n\n"
            . "Booking Anda berhasil dibuat:\n"
            . "Tanggal: {$date}\n"
            . "Nomor Antrian: *{$booking->formatted_queue_number}*\n"
            . "Kategori: {$category}\n\n"
            . "Mohon datang tepat waktu untuk check-in.\n"
            . "Terima kasih.\n\n"
            . "_{$clinic}_";

        return $this->send($booking->user->phone, $message);
    }

    public function bookingCancelled(Booking $booking, string $byLabel): bool
    {
        $clinic = config("services.fonnte.clinic_name");
        $date = Carbon::parse($booking->booking_date)
            ->locale("id")
            ->isoFormat("dddd, D MMMM Y");

        $message = "Halo {$booking->user->name},\n\n"
            . "Booking Anda untuk tanggal {$date} (No. Antrian {$booking->formatted_queue_number}) "
            . "telah *DIBATALKAN* oleh {$byLabel}.\n";

        if ($booking->cancellation_reason) {
            $message .= "Alasan: {$booking->cancellation_reason}\n";
        }

        $message .= "\nSilakan booking ulang jika diperlukan.\n\n_{$clinic}_";

        return $this->send($booking->user->phone, $message);
    }

    public function queueCalled(Booking $booking): bool
    {
        $clinic = config("services.fonnte.clinic_name");
        $message = "Halo {$booking->user->name},\n\n"
            . "*Giliran Anda telah tiba!*\n"
            . "Nomor antrian *{$booking->formatted_queue_number}* dipanggil sekarang.\n"
            . "Silakan menuju ruang pemeriksaan.\n\n"
            . "_{$clinic}_";

        return $this->send($booking->user->phone, $message);
    }

    public function serviceFinished(Booking $booking): bool
    {
        $clinic = config("services.fonnte.clinic_name");
        $message = "Halo {$booking->user->name},\n\n"
            . "Pelayanan untuk nomor antrian {$booking->formatted_queue_number} telah selesai.\n"
            . "Terima kasih atas kunjungan Anda. Semoga lekas sembuh.\n\n"
            . "_{$clinic}_";

        return $this->send($booking->user->phone, $message);
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }
        $digits = preg_replace("/\D+/", "", $phone);
        if (str_starts_with($digits, "0")) {
            return "62" . substr($digits, 1);
        }
        if (str_starts_with($digits, "62")) {
            return $digits;
        }
        return $digits;
    }
}
