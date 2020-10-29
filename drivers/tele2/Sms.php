<?php

namespace mrssoft\sms\drivers\tele2;

use GuzzleHttp\Client;
use mrssoft\sms\Response;

/**
 * Extension for sending SMS through Tele2 SMS-Таргет HTTP API
 * @version 1.0.0
 */
class Sms extends \mrssoft\sms\Sms
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $apiUrl = 'https://target.tele2.ru/api/v2/';

    /**
     * @var string
     */
    public $naming;

    /**
     * The SendMessage function allows you to send a message to a subscriber connected to the service.
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

    /**
     * The SendMessages function allows you to send the same messages to several subscribers connected to the service.
     * @param string $message
     * @param array $phones
     * @param null|string $naming
     * @return Response
     */
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

    private function request(string $function, array $params): Response
    {
        $response = new Response();
        $client = new Client();

        $httpResponse = $client->request('POST', $this->apiUrl . $function, [
            'json' => $params,
            'auth' => [$this->login,  $this->password]
        ]);

        if ($httpResponse->getStatusCode() == 200) {
            $json = json_decode($httpResponse->getBody(), true);
            if ($json['status'] == 'error') {
                $response->error = $json['reason'];
            } else {
                $response->id = $json['result']['uid'];
            }
        } else {
            $response->error = 'Error send request.';
        }

        return $response;
    }
}