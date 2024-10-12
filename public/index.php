<?php
require '../vendor/autoload.php';
require '../src/Database.php';
require '../src/GroupController.php';
require '../src/UserController.php';
require '../src/MessageController.php';

use Slim\Factory\AppFactory;

// create Slim App
$app = AppFactory::create();


$dbConnection = Database::getInstance()->getConnection();

$groupController = new GroupController();
$userController = new UserController();
$messageController = new MessageController();

// define routes
$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write(file_get_contents('../public/index.html'));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->post('/groups', function ($request, $response, $args) use ($groupController) {
    return $groupController->createGroup($request, $response, $args);
});

$app->post('/join_group', function ($request, $response, $args) use ($groupController) {
    return $groupController->joinGroup($request, $response, $args);
});

$app->post('/users', function ($request, $response, $args) use ($userController) {
    return $userController->createUser($request, $response, $args);
});

$app->post('/messages', function ($request, $response, $args) use ($messageController) {
    return $messageController->sendMessage($request, $response, $args);
});

$app->get('/groups/{group_name}/messages', function ($request, $response, $args) use ($messageController) {
    return $messageController->listMessages($request, $response, $args);
});

$app->run();
