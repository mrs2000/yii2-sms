<?php

namespace mrssoft\sms\tests;

use mrssoft\sms\drivers\tele2\Sms;

class SmsTele2Test extends TestBase
{
    protected $driver = 'tele2';
    protected $className = Sms::class;
}