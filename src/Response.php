<?php

namespace mrssoft\sms;

final class Response
{
    public ?string $error = null;

    /**
     * ID sms message
     */
    public ?string $id = null;
}