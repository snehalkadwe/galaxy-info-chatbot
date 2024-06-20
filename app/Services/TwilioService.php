<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(config('chatbot-configs.twilio.sid'), config('chatbot-configs.twilio.auth_token'));
        $this->from = config('chatbot-configs.twilio.from');
    }

    public function sendWhatsAppMessage($to, $message)
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return response()->json(['message' => 'message sent successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
