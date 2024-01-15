<?php

namespace mrssoft\sms;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use yii\base\Component;

abstract class SmsDriver extends Component
{
    abstract public function sendMessage(string $message, string $phone, ?string $naming = null): Response;

    protected function preparePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (mb_strlen($phone) === 10) {
            return '7' . $phone;
        }
        if (str_starts_with($phone, '8')) {
            return '7' . mb_substr($phone, 1);
        }
        return $phone;
    }

    public function sendMessages(string $message, array $phones, ?string $naming = null): Response
    {
        $result = new Response();
        foreach ($phones as $phone) {
            $result = $this->sendMessage($message, $phone, $naming);
            if ($result->error) {
                return $result;
            }
        }

        return $result;
    }

    protected function prepareNaming(?string $naming = null): ?string
    {
        if ($naming) {
            return $naming;
        }

        return $this->naming ?? null;
    }

    protected function createResponse(ResponseInterface $httpResponse): Response
    {
        $response = new Response();
        if ($httpResponse->getStatusCode() !== 200) {
            $response->error = 'Error send request: ' . $httpResponse->getReasonPhrase();
        }
        return $response;
    }

    protected function responseParams(ResponseInterface $httpResponse): ?array
    {
        if ($httpResponse->getStatusCode() === 200) {
            return json_decode($httpResponse->getBody(), true);
        }
        return null;
    }

    protected function httpClient(): Client
    {
        return new Client();
    }
}