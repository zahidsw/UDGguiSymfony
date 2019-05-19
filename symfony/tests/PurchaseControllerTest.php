<?php


namespace App\Tests\PurchaseControllerTest;

use App\Entity\Gui\City;
use App\Entity\Gui\User;
use App\Service\UserManagement;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class PurchaseControllerTest extends KernelTestCase
{


    public function setUp()
    {
        self::bootKernel();
        $this->truncateEntities([
            User::class,
            City::class
        ]);
    }


    public function testAdd()
    {
        $client = new \GuzzleHttp\Client();
        $token = $this->encrypt();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $res = $client->request('POST', 'http://symfony.localhost/purchase', ['headers' => $headers]);
        $this->assertEquals(201, $res->getStatusCode());
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


    private function encrypt()
    {
        $key = "9yUCrlExdstKquikKe7hO44O1ze1wyWUIHp9ZjQY";

        $token = array(
            'timestamp' => 1556527564,
            'service_id' => 'SIPM',
            'service_description' => 'Synchronicity IoT Product Marketplace',
            'request_url' => 'https://0.0.0.0',
            'order_number' => '39',
            'customer' =>
                array(
                    'first_name' => 'John',
                    'last_name' => 'Client',
                    'email' => 'carougeresident@mail.com',
                    'street' => 'Buchanan St. 21',
                    'city' => 'carouge',
                    'state' => 'California',
                    'zip_code' => '94147',
                    'country' => 'United States',
                    'country_code' => 'US',
                    'phone' => '',
                ),
            'product' =>
                array(
                    'sku' => 'udgaas-basic',
                    'name' => 'udgaas-basic',
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
