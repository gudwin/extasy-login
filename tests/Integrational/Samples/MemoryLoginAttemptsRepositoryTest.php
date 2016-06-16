<?php
namespace Extasy\Login\Integrational\Samples;

use \Extasy\Login\tests\Integrational\LoginAttemptsRepositoryTest;
use Extasy\Login\tests\Samples\MemoryLoginAttemptsRepository;

class MemoryLoginAttemptsRepositoryTest extends LoginAttemptsRepositoryTest
{
    protected function loginAttemptsRepositoryFactory() {
        return new MemoryLoginAttemptsRepository();
    }
}