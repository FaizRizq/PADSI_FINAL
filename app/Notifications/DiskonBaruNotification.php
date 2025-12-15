<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DiskonBaruNotification extends Notification
{
    use Queueable;

    protected $diskon;

    public function __construct($diskon)
    {
        $this->diskon = $diskon;
    }

    public function via($notifiable)
    {
        return ['database']; // Bisa ganti ke 'mail' kalau mau email
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Diskon Baru!',
            'body' => 'Diskon ' . $this->diskon->nama_diskon . ' telah tersedia.',
            'jumlah_potongan' => $this->diskon->jumlah_potongan,
            'tanggal_mulai' => $this->diskon->tanggal_mulai,
            'tanggal_selesai' => $this->diskon->tanggal_selesai,
        ];
    }
}
