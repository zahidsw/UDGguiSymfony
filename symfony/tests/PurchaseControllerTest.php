<?php


namespace App\Tests\PurchaseControllerTest;

use App\Entity\Gui\City;
use App\Entity\Gui\Purchase;
use App\Entity\Gui\User;
use App\Service\UserManagement;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;




class PurchaseControllerTest extends WebTestCase
{

    public function setUp()
    {
        self::bootKernel(['environment'=> 'dev']);
    }

    public function getAddSpecificationTest()
    {
        return [
            ['user-test1@mail.com','carouge','udgaas-basic',time()],
            /*['user-test@mail.com','carouge','udgaas-pro',time()],
            ['user-test1@mail.com','bern','udgaas-basic',time()]*/
        ];
    }

    /**
     * @dataProvider getAddSpecificationTest
     */
   /* public function testAdd(String $email, String $city, String $sku, String $timestamp)
    {
        $client = new \GuzzleHttp\Client();
        $token = $this->encrypt($email, $city, $sku, $timestamp);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $res = $client->request('POST', 'http://symfony.localhost/purchase', ['headers' => $headers, 'synchronous' => true]);
        $this->assertEquals(201, $res->getStatusCode());

        $em = $this->getEntityManager('gui');
        $userDb = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        $cityDb = $em->getRepository(City::class)->findOneBy(['name' => $city]);
        $purchaseDb = $em->getRepository(Purchase::class)->findOneBy(['timestamp' => $timestamp]);

        $this->assertSame($userDb->getEmail(), $email);
        $this->assertSame($cityDb->getName(), $city);
        $this->assertSame($purchaseDb->getTimestamp(), $timestamp);

        //clean up
        $userManager = self::$kernel->getContainer()
            ->get( UserManagement::class);

        $keyrockId = $userDb->getKeyrockId();
        $userManager->deleteUserKeyRock($keyrockId);
        $em->remove($userDb);
        $em->flush();
    }*/

    /**
     * @dataProvider getAddSpecificationTest
     */
    public function testAddMail(String $email, String $city, String $sku, String $timestamp)
    {
        $token = $this->encrypt($email, $city, $sku, $timestamp);
        $client = $this->makeClient();
        $crawler = $client->request('POST', 'http://symfony.localhost/purchase', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ]);

        $this->assertStatusCode(201, $client);

        $em = $this->getEntityManager('gui');
        $userDb = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        $cityDb = $em->getRepository(City::class)->findOneBy(['name' => $city]);
        $purchaseDb = $em->getRepository(Purchase::class)->findOneBy(['timestamp' => $timestamp]);
        $this->assertSame($userDb->getEmail(), $email);
        $this->assertSame($cityDb->getName(), $city);
        $this->assertSame($purchaseDb->getTimestamp(), $timestamp);
        //clean up
        $userManager = self::$kernel->getContainer()
            ->get( UserManagement::class);

        $keyrockId = $userDb->getKeyrockId();
        $userManager->deleteUserKeyRock($keyrockId);
        $em->remove($userDb);
        $em->flush();
    }





    /**
     * @param array $entities
     */
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

    /**
     * @return mixed
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @param String $email
     * @param String $city
     * @param String $sku
     * @return string
     */
    private function encrypt(String $email, String $city, String $sku, String $timestamp): String
    {
        $key = "9yUCrlExdstKquikKe7hO44O1ze1wyWUIHp9ZjQY";

        $token = array(
            'timestamp' => $timestamp,
            'service_id' => 'SIPM',
            'service_description' => 'Synchronicity IoT Product Marketplace',
            'request_url' => 'https://0.0.0.0',
            'order_number' => '39',
            'customer' =>
                array(
                    'first_name' => 'John',
                    'last_name' => 'Client',
                    'email' => $email,
                    'street' => 'Buchanan St. 21',
                    'city' => $city,
                    'state' => 'Country',
                    'zip_code' => '94147',
                    'country' => 'Country',
                    'country_code' => 'US',
                    'phone' => '',
                ),
            'product' =>
                array(
                    'sku' => $sku, //'udgaas-basic'
                    'name' => $sku,
                    'quantity' => 1,
                    'price' => 0.0,
                    'currency' => 'CHF',
                    'fields' =>
                        array(
                            'callback_url' => 'http://10.4.17.161/purchase',
                            'callback_field_1' => 'Field 1 value',
                            'callback_field_2' => 'Field 2 value',
                        ),
                ),
        );

        $jwt = JWT::encode($token, $key, 'HS256');
        return $jwt;

    }

}
