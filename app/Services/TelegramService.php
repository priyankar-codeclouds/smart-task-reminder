<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(string $message): Response|array
    {
        $botToken = config('telegram.bot_token');
        $chatId = config('telegram.chat_id');

        if (! filled($botToken) || ! filled($chatId)) {
            return [
                'ok' => false,
                'error' => 'Telegram configuration is missing.',
                'bot_token_present' => filled($botToken),
                'chat_id_present' => filled($chatId),
            ];
        }

        $httpClient = Http::when(app()->environment('local'), function ($client) {
            // Local development only: bypass SSL verification to avoid cURL error 60
            // with self-signed certificate chains on some Windows/PHP setups.
            return $client->withoutVerifying();
        });

        return $httpClient->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }
}
