<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class GroupControllerTest extends TestCase
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

    public function testCreateGroupSuccess()
    {
        $response = self::$client->post('/groups', [
            'form_params' => ['name' => 'Test Group']
        ]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateGroupAlreadyExists()
    {
        // trying to create the same group again
        $response = self::$client->post('/groups', [
            'form_params' => ['name' => 'Test Group'],
            'http_errors' => false // ensuring the test case does not throw an error
        ]);
        $this->assertEquals(409, $response->getStatusCode());
    }

    public function testJoinGroupSuccess()
    {
        // create user to join the group
        self::$client->post('/users', [
            'form_params' => ['username' => 'Test User']
        ]);

        // try to join the group
        $response = self::$client->post('/join_group', [
            'form_params' => [
                'username' => 'Test User',
                'groupname' => 'Test Group'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testJoinGroupAlreadyJoinedGroup()
    {
        // try to join the group
        $response = self::$client->post('/join_group', [
            'form_params' => [
                'username' => 'Test User',
                'groupname' => 'Test Group'
            ],
            'http_errors' => false // ensuring the test case does not throw an error
        ]);

        $this->assertEquals(409, $response->getStatusCode());
    }

    public function testJoinGroupUserNotFound()
    {
        // try to join the group with a non existent user
        $response = self::$client->post('/join_group', [
            'form_params' => [
                'username' => 'nonexistentuser',
                'groupname' => 'Nonexistent User Group'
            ],
            'http_errors' => false // ensuring the test case does not throw an error
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testJoinGroupGroupNotFound()
    {
        // try to join the group with a non existent user
        $response = self::$client->post('/join_group', [
            'form_params' => [
                'username' => 'nonexistentuser',
                'groupname' => 'Nonexistent User Group'
            ],
            'http_errors' => false // ensuring the test case does not throw an error
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }
}
