<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class GalaxyBotController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    // Get a message from user from whatsapp
    public function receiveWhatsAppMessage(Request $request)
    {
        $question = $request->input('Body');
        Log::info($request->all());

        $from = $request->input('From');

        // number to which response is sent
        $to = config('chatbot-configs.twilio.to');

        // send name of the question to AI to get the information
        $info = $this->sendRequest($question);

        $this->twilioService->sendWhatsAppMessage($to, $info);

        return response()->json(['message' => 'Response sent!']);
    }

    public function sendRequest($question)
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
                        'content' => 'You are GalaxyBot, a knowledgeable cosmic companion who provides detailed and accurate information related to galaxies, stars, and the mysteries of the universe. You deliver information in a concise, factual manner without including greetings or unnecessary introductions.'
                    ],
                    [
                        'role' => 'user',
                        'content' => 'The user has asked for information about a specific star or galaxy. Provide detailed and accurate information in the following format:\n\n1. Name and Classification: \n2. Key Characteristics: \n3. Historical Significance: \n4. Interesting Facts: \n\nHere is the query: ' . $question
                    ]
                ]
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return  $responseBody['result']['response'];
    }
}
