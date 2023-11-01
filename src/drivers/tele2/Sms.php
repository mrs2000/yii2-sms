<?php

namespace mrssoft\sms\drivers\tele2;

use mrssoft\sms\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Extension for sending SMS through Tele2 SMS-Таргет HTTP API
 */
final class Sms extends \mrssoft\sms\SmsDriver
{
    public string $login;
    public string $password;
    public string $naming;
    public string $apiUrl = 'https://target.tele2.ru/api/v2/';

    /**
     * Timeout, seconds
     */
    public int $timeout = 0;

    /**
     * @param string $message
     * @param string $phone
     * @param null|string $naming
     * @return Response
     */
    public function sendMessage(string $message, string $phone, ?string $naming = null): Response
    {
        $params['msisdn'] = $this->preparePhone($phone);
        $params['shortcode'] = $this->prepareNaming($naming);
        $params['text'] = $message;

        return $this->request('send_message', $params);
    }

    protected function createResponse(ResponseInterface $httpResponse): Response
    {
        if ($params = $this->responseParams($httpResponse)) {
            $response = new Response();
            if ($params['status'] == 'error') {
                $response->error = $params['reason'];
            } else {
                $response->id = $params['result']['uid'];
            }
            return $response;
        }

        return parent::createResponse($httpResponse);
    }

    private function request(string $function, array $params): Response
    {
        $httpResponse = $this->httpClient()
                             ->request('POST', $this->apiUrl . $function, [
                                 'json' => $params,
                                 'auth' => [$this->login, $this->password],
                                 'timeout' => $this->timeout,
                                 'verify' => false,
                             ]);

        return $this->createResponse($httpResponse);
    }
}