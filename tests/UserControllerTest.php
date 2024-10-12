<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class UserControllerTest extends TestCase
{
    private static $client;
    private static $db;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client(['base_uri' => 'http://localhost:8080']);

        self::$db = new PDO('sqlite:' . __DIR__ . '/../data/database.sqlite');

        // start tests with empty db
        self::$db->exec('DELETE FROM users');
        self::$db->exec('DELETE FROM groups');
        self::$db->exec('DELETE FROM user_groups');
        self::$db->exec('DELETE FROM messages');
    }

    public function testCreateUserSuccess()
    {
        $response = self::$client->post('/users', [
            'form_params' => ['username' => 'Test User']
        ]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateUserAlreadyExists()
    {
        // trying to create the same user again
        $response = self::$client->post('/users', [
            'form_params' => ['username' => 'Test User'],
            'http_errors' => false // ensuring the test case does not throw an error
        ]);
        $this->assertEquals(409, $response->getStatusCode());
    }
}
