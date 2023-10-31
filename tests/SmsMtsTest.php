<?php

namespace tests;

use mrssoft\sms\drivers\mts\Sms;

final class SmsMtsTest extends TestBase
{
    protected string $driver = 'mts';
    protected string $className = Sms::class;
}