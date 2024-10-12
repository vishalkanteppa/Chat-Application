<?php

require_once 'ResponseHelper.php';


class UserController {
    public function createUser($request, $response, $args) {
        $data = $request->getParsedBody();
        $username = $data['username'];

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (username) VALUES (:username)");

        try {
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        } 
        catch (PDOException $e) {
            if ($e->getCode() === '23000') { // check if the user already exists in db
                return ResponseHelper::createJsonResponse($response, 409);
            } else {
                // internal error
                return ResponseHelper::createJsonResponse($response, 500);
            }
        }

        // return a success response with a 201 status
        return ResponseHelper::createJsonResponse($response, 201);
    }
}
