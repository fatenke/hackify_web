<?php

namespace App\Service;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

class GeminiChatService
//AMIRAAAAA
{
    private HttpClient $httpClient;
    private string $apiKey;
    private string $model = 'gemini-2.0-flash';
    private array $history = [];

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new HttpClient([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        ]);
    }

    public function sendMessage(string $message, ?int $chatId = null): string
    {
        file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "Starting Gemini request for chat ID: $chatId\n", FILE_APPEND);

        // Validate inputs
        if (empty($this->apiKey)) {
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "ERROR: API key is not configured\n", FILE_APPEND);
            throw new \RuntimeException('Gemini API key is not configured');
        }
        if (empty($message)) {
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "ERROR: Message is empty\n", FILE_APPEND);
            throw new \RuntimeException('Message cannot be empty');
        }

        // Initialize history array for this chat if it doesn't exist
        if (!isset($this->history[$chatId ?? 0])) {
            $this->history[$chatId ?? 0] = [];
        }

        // Add user message to history
        $this->history[$chatId ?? 0][] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        // Create payload with conversation history
        $payload = [
            'contents' => $this->history[$chatId ?? 0]
        ];

        file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

        try {
            // Send request to Gemini API - Use the full URL instead of base_uri + path
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "Request URL: $url\n", FILE_APPEND);

            $response = $this->httpClient->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30,
                'verify' => false,
            ]);

            // Parse response
            $responseBody = $response->getBody()->getContents();
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "Response: $responseBody\n", FILE_APPEND);

            $data = json_decode($responseBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "ERROR: JSON decode error: " . json_last_error_msg() . "\n", FILE_APPEND);
                throw new \RuntimeException('Invalid JSON response from Gemini API');
            }
            if (isset($data['error'])) {
                file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "ERROR: API error: " . $data['error']['message'] . "\n", FILE_APPEND);
                throw new \RuntimeException('Gemini API error: ' . $data['error']['message']);
            }
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "ERROR: Invalid response format: " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
                throw new \RuntimeException('Invalid response format from Gemini API');
            }

            $botResponse = $data['candidates'][0]['content']['parts'][0]['text'];
            
            // Add bot response to history
            $this->history[$chatId ?? 0][] = [
                'role' => 'model',
                'parts' => [['text' => $botResponse]]
            ];
            
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "Success: $botResponse\n", FILE_APPEND);
            return $botResponse;
        } catch (RequestException $e) {
            $errorMessage = 'Gemini API Request Exception: ' . $e->getMessage();
            if ($e->hasResponse()) {
                $errorMessage .= ' - Response: ' . $e->getResponse()->getBody()->getContents();
            }
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "$errorMessage\n", FILE_APPEND);
            throw new \RuntimeException($errorMessage);
        } catch (\Exception $e) {
            $errorMessage = 'Unexpected error in Gemini Service: ' . $e->getMessage();
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "$errorMessage\n", FILE_APPEND);
            throw new \RuntimeException($errorMessage);
        }
    }

    /**
     * Set the conversation history
     *
     * @param array $history Array of conversation messages
     * @param int|null $chatId The chat ID to set history for
     */
    public function setHistory(array $history, ?int $chatId = null): void
    {
        $this->history[$chatId ?? 0] = $history;
        file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "History set for chat ID: " . ($chatId ?? 0) . "\n", FILE_APPEND);
    }

    /**
     * Get the current conversation history
     *
     * @param int|null $chatId The chat ID to get history for
     * @return array The conversation history
     */
    public function getHistory(?int $chatId = null): array
    {
        return $this->history[$chatId ?? 0] ?? [];
    }

    /**
     * Clear the conversation history
     *
     * @param int|null $chatId The chat ID to clear history for
     */
    public function clearHistory(?int $chatId = null): void
    {
        $this->history[$chatId ?? 0] = [];
        file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', "History cleared for chat ID: " . ($chatId ?? 0) . "\n", FILE_APPEND);
    }
}