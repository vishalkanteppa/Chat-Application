<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class MessageControllerTest extends TestCase
{
    private static $client;
    private static $db;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client(['base_uri' => 'http://localhost:8080']);

        self::$db = new PDO('sqlite:' . __DIR__ . '/../data/database.sqlite');

        // start tests with an empty db
        self::$db->exec('DELETE FROM users');
        self::$db->exec('DELETE FROM groups');
        self::$db->exec('DELETE FROM user_groups');
        self::$db->exec('DELETE FROM messages');
    }

    public function testSendMessageSuccess()
    {
        // create a user and a group
        self::$client->post('/users', [
            'form_params' => ['username' => 'Test User']
        ]);

        self::$client->post('/groups', [
            'form_params' => ['name' => 'Test Group']
        ]);

        // send a message to the group
        $response = self::$client->post('/messages', [
            'form_params' => [
                'user_name' => 'Test User',
                'group_name' => 'Test Group',
                'content' => 'Hello, World!'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testSendMessageUserNotFound()
    {
        // send a message with non existent user
        $response = self::$client->post('/messages', [
            'form_params' => [
                'user_name' => 'NonExistentUser',
                'group_name' => 'Test Group',
                'content' => 'Hello, World!'
            ],
            'http_errors' => false // ensure the test case does not throw an error
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSendMessageGroupNotFound()
    {
        // send a message to non existent group
        $response = self::$client->post('/messages', [
            'form_params' => [
                'user_name' => 'Test User',
                'group_name' => 'NonExistentGroup',
                'content' => 'Hello, World!'
            ],
            'http_errors' => false // ensure the test case does not throw an error
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testListMessagesSuccess()
    {
        // obtain messages from group
        $response = self::$client->get('/groups/Test Group/messages');

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertNotEmpty($data['messages']);
        $this->assertEquals('Hello, World!', $data['messages'][0]['content']);
    }

    public function testListMessagesGroupNotFound()
    {
        // list messages for non existent group
        $response = self::$client->get('/groups/NonExistentGroup/messages', [
            'http_errors' => false // ensure the test case does not throw an error
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }
}
