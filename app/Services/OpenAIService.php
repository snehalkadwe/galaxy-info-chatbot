<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function getStarInformation($starName)
    {
        return "helllo kiddo";
        // $response = $this->client->completions()->create([
        //     'model' => 'gpt-3.5-turbo-16k',
        //     'prompt' => "Provide detailed information about the star named $starName.",
        //     'max_tokens' => 150,
        // ]);

        // return $response['choices'][0]['text'] ?? 'No information available.';
    }
}
