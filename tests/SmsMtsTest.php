<?php

namespace mrssoft\sms\tests;

use mrssoft\sms\drivers\mts\Sms;

class SmsMtsTest extends TestBase
{
    protected $driver = 'mts';
    protected $className = Sms::class;
}