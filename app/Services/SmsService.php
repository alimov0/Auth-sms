<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SmsService
{
    //  AuthService’dagi SMS logikasini umumiy serviceda jamladim
    public function getToken(): ?string
    {
        $token = Cache::get('eskiz_api_token');
        if (! $token) {
            $response = Http::post('https://notify.eskiz.uz/api/auth/login', [
                'email'    => config('eskiz.eskiz_sms_login'),
                'password' => config('eskiz.eskiz_sms_password'),
            ]);

            if ($response->json('status') === 'success') {
                $token = $response->json('data.token');
                Cache::put('eskiz_api_token', $token, now()->addDays(29));
            } else {
                return null;
            }
        }
        return $token;
    }

    public function send(string $phone, string $text): array
    {
        $token = $this->getToken();
        if (! $token) {
            return ['status' => 'error', 'message' => 'Eskiz auth failed'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('https://notify.eskiz.uz/api/message/sms/send', [
            'mobile_phone' => $phone,
            'message'      => $text,
            'from'         => '4546',
        ]);

        if ($response->json('status') === 'success') {
            return ['status' => 'success', 'message' => 'SMS yuborildi'];
        }

        return [
            'status'  => 'error',
            'message' => $response->json('message') ?? 'Xatolik yuz berdi',
        ];
    }
}
