<?php

namespace App\Helpers;

class Fonnte {
    public static function send($target, $message) {
        $token = "YhxWSjnKPn99C6cApb9e"; // token lu

        $data = [
            'target' => $target,
            'message' => $message
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => ["Authorization: $token"],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
