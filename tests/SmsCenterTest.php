<?php

namespace tests;

use mrssoft\sms\drivers\smscenter\Sms;

final class SmsCenterTest extends TestBase
{
    protected string $driver = 'smscenter';
    protected string $className = Sms::class;
}