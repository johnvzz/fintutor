<?php

namespace App\Services;

use Gemini\Data\GenerationConfig;
use Gemini\Enums\ResponseMimeType;
use Gemini\Laravel\Facades\Gemini;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Throwable;

class GeminiService
{
    public function fetchChat(string $prompt)
    {
        // Extend PHP execution limit to 120 seconds for this request
        set_time_limit(120);

        try {
            $response = Gemini::generativeModel(model: 'gemini-3.6-flash')
                        ->withGenerationConfig(
                            new GenerationConfig(
                                responseMimeType: ResponseMimeType::APPLICATION_JSON
                            )
                        )        
                        ->generateContent($prompt);


            $content = json_decode(
                $response->text(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return [
                'status' => 'success',
                'statusCode' => 200,
                'content' => $content,
            ];           
       } catch (ClientException | RequestException $e) {
            // Handles 4xx / 5xx HTTP responses from Google API
            $googleErrorBody = [];
            
            if ($e->hasResponse()) {
                $rawBody = (string) $e->getResponse()->getBody();
                $googleErrorBody = json_decode($rawBody, true) ?? [];
            }

            // Extract Google's structured error or fall back to exception message
            $errorDetails = $googleErrorBody['error'] ?? [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'status' => 'UNKNOWN_ERROR',
                'details' => [],
            ];

            return [
                'status' => 'error',
                'statusCode' => 500,
                'message' => $errorDetails['message'] ?? 'An API error occurred.',
                'error' => $errorDetails,
            ];

        } catch (Throwable $e) {
            // Handles non-HTTP exceptions (e.g., PHP runtime errors, local SSL errors)
            return [
                'status' => 'error',
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'error' => [
                    'code' => $e->getCode() ?: 500,
                    'message' => $e->getMessage(),
                    'status' => 'INTERNAL_SERVER_ERROR',
                    'details' => [],
                ],
            ];
        }
    }
}