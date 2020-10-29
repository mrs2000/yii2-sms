<?php

namespace mrssoft\sms;

use yii\base\Component;

abstract class Sms extends Component
{
    abstract public function sendMessage(string $message, string $phone): Response;
    abstract public function sendMessages(string $message, array $phones): Response;

    protected function preparePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (mb_strlen($phone) === 10) {
            return '7' . $phone;
        }
        if (mb_strpos($phone, '8') === 0) {
            return '7' . mb_substr($phone, 1);
        }
        return $phone;
    }

    protected function prepareNaming(?string $naming = null): ?string
    {
        return $naming ?? $this->naming;
    }
}