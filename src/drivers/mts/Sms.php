<?php

namespace mrssoft\sms\drivers\mts;

use mrssoft\sms\Response;
use SoapClient;
use SoapFault;

/**
 * Extension for sending SMS through MTS Communicator M2M API
 */
final class Sms extends \mrssoft\sms\SmsDriver
{
    public string $token;

    public string $wsdlUrl = 'https://www.mcommunicator.ru/m2m/m2m_api.asmx?WSDL';

    public string $naming;

    private SoapClient $client;

    public function init(): void
    {
        parent::init();

        $this->client = new SoapClient($this->wsdlUrl, [
            'soap_version' => SOAP_1_2,
            'stream_context' => stream_context_create([
                'http' => [
                    'header' => 'Authorization: Bearer ' . $this->token
                ]
            ])
        ]);
    }

    public function sendMessage(string $message, string $phone, ?string $naming = null): Response
    {
        $params['message'] = $message;
        $params['msid'] = $this->preparePhone($phone);
        if ($naming = $this->prepareNaming($naming)) {
            $params['naming'] = $naming;
        }

        return $this->request('SendMessage', $params);
    }

    public function sendMessages(string $message, array $phones, ?string $naming = null): Response
    {
        $params['message'] = $message;
        $params['msids'] = array_map([$this, 'preparePhone'], $phones);
        if ($naming = $this->prepareNaming($naming)) {
            $params['naming'] = $naming;
        }

        return $this->request('SendMessages', $params);
    }

    private function request(string $function, array $params): Response
    {
        $response = new Response();

        try {
            $this->client->{$function}($params);
        } catch (SoapFault $e) {
            $response->error = $e->getMessage();
        }

        return $response;
    }
}