<?php


namespace Extasy\Login\Integrational\Samples;

use Extasy\Login\tests\Integrational\SessionRepositoryTest;
use Extasy\Login\tests\Samples\MemorySessionRepository;

class MemortySessionRepositoryTest extends SessionRepositoryTest
{
    protected function getSessionRepository()
    {
        return new MemorySessionRepository();
    }
}