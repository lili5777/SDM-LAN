<?php

namespace App\Traits;

use App\Models\Notifikasi;

trait NotifiableCustom
{
    /**
     * Send notification to current user
     */
    public function sendNotification($judul, $pesan, $tipe = 'info', $url = null)
    {
        return Notifikasi::create([
            'user_id' => $this->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'url' => $url,
        ]);
    }

    /**
     * Send notification to specific user
     */
    public function sendNotificationToUser($userId, $judul, $pesan, $tipe = 'info', $url = null)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'url' => $url,
        ]);
    }

    /**
     * Send notification to multiple users
     */
    public function sendNotificationToUsers($userIds, $judul, $pesan, $tipe = 'info', $url = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'judul' => $judul,
                'pesan' => $pesan,
                'tipe' => $tipe,
                'url' => $url,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return Notifikasi::insert($notifications);
    }
}