<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAiService 
{
    public function fetchChat(string $prompt) 
    {
        return Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/responses', [
                        'model' => 'gpt-5.6-luna',
                        'input' => $prompt,
                ])
                ->json();                                   
    }

    public function fetchChatWithout(string $prompt)
    {
         return Http::withoutVerifying()
                ->withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/responses', [
                        'model' => 'gpt-4o',
                        'input' => $prompt,
                ])
                ->json();
    }
}