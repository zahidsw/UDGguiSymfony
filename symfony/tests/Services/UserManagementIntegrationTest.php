<?php

namespace App\Tests\Services\UserManagementIntegrationTest;
use App\Entity\Gui\City;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Gui\User;
use App\Service\UserManagement;



class UserManagementIntegrationTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();
        $this->truncateEntities([
            User::class,
            City::class
        ]);
    }

    public function testGetKeyRockUserReturnEmptyOnNotExistingUser()
    {
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $user = $userManager->getKeyRockUser('notexisting-test@mail.com');
        $this->assertEmpty($user);
    }

    public function testGetKeyRockUserReturnOnExistingUser()
    {
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $user = $userManager->getKeyRockUser('bernadmin@mail.com');
        $this->assertNotEmpty($user);
        $this->assertSame('bernadmin@mail.com', $user['email']);
    }

    public function testGetDbUserReturnNullOnNotExistingUser()
    {
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $mail = 'notexisting-test@mail.com';
        $user = $userManager->getDbUser($mail);
        $this->assertNull($user);
    }

    public function testUserRegistrationKeyRock()
    {
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $email = 'user-test@mail.com';
        $user = 'user-test';
        $password = 'password';
        $user = $userManager->userRegistrationKeyRock($user,$email,$password);
        $this->assertNotNull($user);
        $this->assertSame($email, $user['email']);
        //clean up
        $userManager->deleteUserKeyRock($user['id']);
    }

    public function testAddUserOnNotExistingUserInDbAndNotExistingUserInKeyRock()
    {
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $email = 'user-test@mail.com';
        $city = 'city-test';
        $user = $userManager->addUser($email,$city);
        $em = $this->getEntityManager();
        $userDb = $em->getRepository(User::class)->findOneBy(['id' => $user['dbUser']['id']]);
        $this->assertNotNull($user['dbUser']['id']);
        $this->assertNotNull($user['keyrockUser']['id']);
        $this->assertSame($userDb->getKeyrockId(), $user['keyrockUser']['id']);
        $this->assertFalse($userDb->isEnabled());
        //clean up
        $userManager->deleteUserKeyRock($user['keyrockUser']['id']);
        // delete organization todo
    }


    public function testAddUserOnExistingUserInDbAndNotExistingUserInKeyRock()
    {
        $email = 'user-test@mail.com';
        $userTest = $this->addDbUser($email);
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $city = 'city-test';
        $user = $userManager->addUser($email,$city);
        $em = $this->getEntityManager();
        $userDb = $em->getRepository(User::class)->findOneBy(['id' => $user['dbUser']['id']]);
        $this->assertNotNull($user['dbUser']['id']);
        $this->assertNotNull($user['keyrockUser']['id']);
        $this->assertSame($userTest->getId(),$user['dbUser']['id']);
        $this->assertSame($userDb->getKeyrockId(), $user['keyrockUser']['id']);
        //clean up
        $userManager->deleteUserKeyRock($user['keyrockUser']['id']);
        // delete organization todo
    }

    public function testAddUserOnNotExistingUserInDbAndExistingUserInKeyRock()
    {

        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $user = 'user-test';
        $email = 'user-test@mail.com';
        $password= 'password';
        $userTest = $userManager->userRegistrationKeyRock($user,$email,$password);
        $city = 'city-test';
        $user = $userManager->addUser($email,$city);
        $em = $this->getEntityManager();
        $userDb = $em->getRepository(User::class)->findOneBy(['id' => $user['dbUser']['id']]);
        $this->assertNotNull($user['dbUser']['id']);
        $this->assertNotNull($user['keyrockUser']['id']);
        $this->assertSame($userTest['id'],$user['keyrockUser']['id']);
        $this->assertSame($userDb->getKeyrockId(), $user['keyrockUser']['id']);
        $this->assertFalse($userDb->isEnabled());
        //clean up
        $userManager->deleteUserKeyRock($user['keyrockUser']['id']);
        // delete organization todo
    }

    public function testAddUserOnExistingUserInDbAndExistingUserInKeyRock()
    {

        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);

        $user = 'user-test';
        $email = 'user-test@mail.com';
        $password= 'password';
        $userTestDb = $this->addDbUser($email);
        $userTestKeyrock = $userManager->userRegistrationKeyRock($user,$email,$password);
        $city = 'city-test';
        $user = $userManager->addUser($email,$city);
        $em = $this->getEntityManager();
        $userDb = $em->getRepository(User::class)->findOneBy(['id' => $user['dbUser']['id']]);
        $this->assertNotNull($user['dbUser']['id']);
        $this->assertNotNull($user['keyrockUser']['id']);
        $this->assertSame($userTestKeyrock['id'],$user['keyrockUser']['id']);
        $this->assertSame($userTestDb->getId(),$user['dbUser']['id']);
        $this->assertSame($userDb->getKeyrockId(), $user['keyrockUser']['id']);
        //clean up
        $userManager->deleteUserKeyRock($user['keyrockUser']['id']);
        // delete organization todo
    }

    public function testUserRegistrationDatabase()
    {

        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $mail = 'user-test@mail.com';
        $keyRockId = '45091fd63a1-test';
        $user = $userManager->userRegistrationDatabase($mail,$keyRockId);
        $this->assertNotNull($user);
        $this->assertSame($mail, $user->getEmail());
    }

    public function testGetDbUserReturnOnExistingUser()
    {
        $mail = 'user-test@mail.com';
        $this->addDbUser($mail);
        $userManager = self::$kernel->getContainer()
            ->get('test.'. UserManagement::class);
        $user = $userManager->getDbUser($mail);
        $this->assertNotNull($user);
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    private function addDbUser(String $email)
    {
        $em = $this->getEntityManager();
        $user = new User();
        $user->setUserName($email);
        $user->setEmail($email);
        $user->setEnabled(true);
        $userRole = ['ROLE_USER','ROLE_ADMIN'];
        $user->addRole(implode(",",$userRole));
        $user->setKeyrockId('12345-test');
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
