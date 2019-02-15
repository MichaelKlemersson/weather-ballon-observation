<?php

namespace Test\WbApp\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

class BaseTestCase extends TestCase
{
    protected $application;

    public function setUp(): void
    {
        $this->application = new Application();
    }
}
