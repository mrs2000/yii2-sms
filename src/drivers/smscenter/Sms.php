<?php

namespace mrssoft\sms\drivers\smscenter;

use GuzzleHttp\Client;
use mrssoft\sms\Response;

/**
 * Extension for sending SMS through MTS Communicator M2M API
 * @version 2.0.0
 */
final class Sms extends \mrssoft\sms\Sms
{
    public string $login;
    public string $password;
    public string $naming;
    public string $apiUrl = 'https://smsc.ru/';

    /**
     * Timeout, seconds
     */
    public int $timeout = 0;

    /**
     * The SendMessage function allows you to send a message to a subscriber connected to the service.
     */
    public function sendMessage(string $message, string $phone, ?string $naming = null): Response
    {
        $params['mes'] = $message;
        $params['phones'] = $this->preparePhone($phone);
        if ($naming = $this->prepareNaming($naming)) {
            $params['sender'] = $naming;
        }

        return $this->request('rest/send/', $params);
    }

    /**
     * The SendMessages function allows you to send the same messages to several subscribers connected to the service.
     */
    public function sendMessages(string $message, array $phones, ?string $naming = null): Response
    {
        $params['mes'] = $message;
        $params['phones'] = implode(',', array_map([$this, 'preparePhone'], $phones));
        if ($naming = $this->prepareNaming($naming)) {
            $params['sender'] = $naming;
        }

        return $this->request('rest/send/', $params);
    }

    private function request(string $function, array $params): Response
    {
        $response = new Response();
        $client = new Client();

        $params['login'] = $this->login;
        $params['psw'] = $this->password;
        $params['fmt'] = 3; //response JSON format

        $httpResponse = $client->request('POST', $this->apiUrl . $function, [
            'json' => $params
        ]);

        if ($httpResponse->getStatusCode() === 200) {
            $json = json_decode($httpResponse->getBody(), true);
            if (isset($json['error'])) {
                $response->error = $json['error'] . ' [code: ' . $json['error_code'] . ']';
            } else {
                $response->id = $json['id'];
            }
        } else {
            $response->error = 'Error send request.';
        }

        return $response;
    }
}