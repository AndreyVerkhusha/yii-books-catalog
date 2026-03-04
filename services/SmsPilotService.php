<?php

namespace app\services;

use RuntimeException;

class SmsPilotService
{
    public function send(string $phone, string $message): void
    {
        $apiKey = (string) (\Yii::$app->params['smspilotApiKey'] ?? '');

        if ($apiKey === '') {
            throw new RuntimeException('SMSPILOT API key is not configured.');
        }

        $queryParams = http_build_query([
            'apikey' => $apiKey,
            'send' => $message,
            'to' => $phone,
            'format' => 'json',
            'test' => 1,
        ]);

        $requestUrl = 'https://smspilot.ru/api.php?' . $queryParams;

        $responseBody = @file_get_contents($requestUrl);

        if ($responseBody === false) {
            throw new RuntimeException('SMSPILOT request failed.');
        }

        $responseData = json_decode($responseBody, true);

        if (!is_array($responseData)) {
            throw new RuntimeException('Invalid SMSPILOT response.');
        }

        if (isset($responseData['error'])) {
            $errorDescription = (string) ($responseData['error']['description_ru'] ?? $responseData['error']['description'] ?? 'Unknown SMSPILOT error.');

            throw new RuntimeException($errorDescription);
        }
    }
}