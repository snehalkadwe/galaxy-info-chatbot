<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\TwilioService;

class GalaxyBotController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function receiveWhatsAppMessage(Request $request)
    {
        $planet = $request->input('body');
        $to = config('chatbot-configs.twilio.to');

        $info = $this->sendRequest($planet);

        $this->twilioService->sendWhatsAppMessage($to, $info);

        return response()->json(['message' => 'Response sent!']);
    }

    public function sendRequest($planet)
    {
        $accountId = config('chatbot-configs.cloudflare.account_id');

        $client = new Client();
        $url = 'https://api.cloudflare.com/client/v4/accounts/' . $accountId . '/ai/run/@cf/meta/llama-2-7b-chat-int8';
        $authorizationToken = config('chatbot-configs.cloudflare.api_key');

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => "Bearer $authorizationToken",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You have to provides history of the planet provided by the user'
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Provide detailed information about the star named ' . $planet
                    ]
                ]
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return  $responseBody['result']['response'];
    }
}
