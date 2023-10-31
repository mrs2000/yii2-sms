<?php

namespace tests;

class TestBase extends \PHPUnit\Framework\TestCase
{
    private array $params;
    private array $driverParams;

    protected string $driver;
    protected string $className;

    private function loadParams(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $filename), true);
    }

    public function setUp(): void
    {
        $this->params = $this->loadParams('params.json');
        $this->driverParams = $this->loadParams('params.' . $this->driver . '.json');
    }

    public function testSendMessage(): void
    {
        $driver = $this->className;
        $api = new $driver($this->driverParams);
        $response = $api->sendMessage('Test ' . random_int(1, 1000), $this->params['phone'], $this->params['naming'] ?? null);

        self::assertEmpty($response->error);
    }
}