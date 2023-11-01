<?php

namespace mrssoft\sms\drivers\novator;

use mrssoft\sms\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Extension for sending SMS through Novator Gateway
 */
final class Sms extends \mrssoft\sms\SmsDriver
{
    public string $token;
    public string $apiUrl;

    public string $channel = '';
    public string $naming = '';

    public function sendMessage(string $message, string $phone, ?string $naming = null): Response
    {
        $params['text'] = $message;
        $params['phone'] = $this->preparePhone($phone);
        if ($naming = $this->prepareNaming($naming)) {
            $params['sender'] = $naming;
        }

        return $this->request($params);
    }

    protected function createResponse(ResponseInterface $httpResponse): Response
    {
        if ($params = $this->responseParams($httpResponse)) {
            $response = new Response();
            if (isset($json['error'])) {
                $response->error = $params['error'];
            } else {
                $response->id = $params['id'];
            }
            return $response;
        }

        return parent::createResponse($httpResponse);
    }

    private function request(array $params): Response
    {
        $params['token'] = $this->token;
        $params['channel'] = $this->channel ?? 'default';
        $params['naming'] = $this->naming ?? 'default';

        $httpResponse = $this->httpClient()
                             ->request('POST', $this->apiUrl, [
                                 'json' => $params
                             ]);

        return $this->createResponse($httpResponse);
    }
}