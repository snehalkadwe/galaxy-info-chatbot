<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;
use App\Services\OpenAIService;

class GalaxyBotController extends Controller
{
    protected $twilioService;
    protected $openAIService;

    public function __construct(TwilioService $twilioService, OpenAIService $openAIService)
    {
        $this->twilioService = $twilioService;
        $this->openAIService = $openAIService;
    }

    public function receiveWhatsAppMessage(Request $request)
    {

        $starName = $request->input('body');
        $to = env('TWILIO_WHATSAPP_TO');

        $info = $this->openAIService->getStarInformation($starName);

        $this->twilioService->sendWhatsAppMessage($to, $info);

        return response()->json(['message' => 'Response sent!']);
    }
}
