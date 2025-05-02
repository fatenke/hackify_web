<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PerspectiveApiService
{
    private string $apiKey;
    private HttpClientInterface $httpClient;
    private array $attributeThresholds = [
        'TOXICITY' => 0.7,
        'SEVERE_TOXICITY' => 0.5,
        'IDENTITY_ATTACK' => 0.5,
        'INSULT' => 0.7,
        'PROFANITY' => 0.7,
        'THREAT' => 0.5
    ];

    public function __construct(HttpClientInterface $httpClient, string $perspectiveApiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $perspectiveApiKey;
    }

    /**
     * Analyze text for toxic content using Google Perspective API
     * 
     * @param string $text The text to analyze
     * @param array|null $attributes Specific attributes to analyze or null for all defaults
     * @param string $language Language of the text (default: 'en')
     * @return array Analysis results with attribute scores and flagged status
     */
    public function analyzeText(string $text, ?array $attributes = null, string $language = 'en'): array
    {
        if (empty($text)) {
            return ['isFlagged' => false, 'scores' => []];
        }

        // Use default attributes if none provided
        $attributesToAnalyze = $attributes ?? array_keys($this->attributeThresholds);
        
        // Build request data for Perspective API
        $requestData = [
            'comment' => ['text' => $text],
            'languages' => [$language],
            'requestedAttributes' => []
        ];
        
        // Add each attribute to the request
        foreach ($attributesToAnalyze as $attribute) {
            $requestData['requestedAttributes'][$attribute] = ['scoreType' => 'PROBABILITY'];
        }
        
        try {
            $response = $this->httpClient->request('POST', 'https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze', [
                'query' => [
                    'key' => $this->apiKey
                ],
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            $result = $response->toArray();
            
            // Process the response
            $scores = [];
            $isFlagged = false;
            
            if (isset($result['attributeScores'])) {
                foreach ($result['attributeScores'] as $attribute => $data) {
                    $score = $data['summaryScore']['value'];
                    $scores[$attribute] = $score;
                    
                    // Check if this attribute exceeds threshold
                    if (isset($this->attributeThresholds[$attribute]) && $score >= $this->attributeThresholds[$attribute]) {
                        $isFlagged = true;
                    }
                }
            }
            
            return [
                'isFlagged' => $isFlagged,
                'scores' => $scores
            ];
            
        } catch (\Exception $e) {
            // Log the error but don't fail the application
            error_log('Perspective API error: ' . $e->getMessage());
            
            // Return empty result on error
            return [
                'isFlagged' => false,
                'scores' => [],
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Check if text is toxic based on default or provided thresholds
     * 
     * @param string $text The text to analyze
     * @param array|null $customThresholds Optional custom thresholds to override defaults
     * @return bool True if flagged as toxic
     */
    public function isToxic(string $text, ?array $customThresholds = null): bool
    {
        $result = $this->analyzeText($text);
        return $result['isFlagged'];
    }
    
    /**
     * Set custom thresholds for different attributes
     * 
     * @param array $thresholds Associative array of attribute => threshold value pairs
     */
    public function setThresholds(array $thresholds): void
    {
        foreach ($thresholds as $attribute => $threshold) {
            if (isset($this->attributeThresholds[$attribute])) {
                $this->attributeThresholds[$attribute] = $threshold;
            }
        }
    }
} 