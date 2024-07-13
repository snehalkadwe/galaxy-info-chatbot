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
        $sId = config('chatbot-configs.twilio.sid');
        $authToken = config('chatbot-configs.twilio.auth_token');

        $this->client = new Client($sId, $authToken);
        $this->from = config('chatbot-configs.twilio.from');
    }

    // Send the response received from AI model to the user on whatsapp sandbox
    public function sendWhatsAppMessage($to, $information)
    {
        Log::info(['to' => $to, 'from' => $this->from]);
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $information,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
