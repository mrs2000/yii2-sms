<?php

namespace tests;

use mrssoft\sms\drivers\tele2\Sms;

final class SmsTele2Test extends TestBase
{
    protected string $driver = 'tele2';
    protected string $className = Sms::class;
}