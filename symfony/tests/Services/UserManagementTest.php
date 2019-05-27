<?php

// tests/Services/UserManagementTest.php
namespace App\Tests\Services\UserManagementTest;


use App\Service\UserManagement;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserManagementTest  extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();

    }
    public function testGenerateRegistrationToken()
    {
         $userManager = self::$kernel->getContainer()
             ->get('test.'. UserManagement::class);
         $uuid = $userManager->generateRegistrationToken();
         $this->assertNotEmpty($uuid);
    }

 }
