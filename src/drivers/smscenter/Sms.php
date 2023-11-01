<?php

namespace mrssoft\sms\drivers\smscenter;

use mrssoft\sms\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Extension for sending SMS through SmsCenter
 */
final class Sms extends \mrssoft\sms\SmsDriver
{
    public string $login;
    public string $password;
    public string $naming;
    public string $apiUrl = 'https://smsc.ru/';

    /**
     * Timeout, seconds
     */
    public int $timeout = 0;

    public function sendMessage(string $message, string $phone, ?string $naming = null): Response
    {
        $params['mes'] = $message;
        $params['phones'] = $this->preparePhone($phone);
        if ($naming = $this->prepareNaming($naming)) {
            $params['sender'] = $naming;
        }

        return $this->request('rest/send/', $params);
    }

    public function sendMessages(string $message, array $phones, ?string $naming = null): Response
    {
        $params['mes'] = $message;
        $params['phones'] = implode(',', array_map([$this, 'preparePhone'], $phones));
        if ($naming = $this->prepareNaming($naming)) {
            $params['sender'] = $naming;
        }

        return $this->request('rest/send/', $params);
    }

    protected function createResponse(ResponseInterface $httpResponse): Response
    {
        if ($params = $this->responseParams($httpResponse)) {
            $response = new Response();
            if (isset($json['error'])) {
                $response->error = $params['error'] . ' [code: ' . $params['error_code'] . ']';
            } else {
                $response->id = $params['id'];
            }
            return $response;
        }

        return parent::createResponse($httpResponse);
    }

    private function request(string $function, array $params): Response
    {
        $params['login'] = $this->login;
        $params['psw'] = $this->password;
        $params['fmt'] = 3; //response JSON format

        $httpResponse = $this->httpClient()
                             ->request('POST', $this->apiUrl . $function, [
                                 'json' => $params,
                                 'timeout' => $this->timeout,
                             ]);

        return $this->createResponse($httpResponse);
    }
}