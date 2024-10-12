<?php

require_once 'ResponseHelper.php';

class GroupController
{
    public function createGroup($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $groupName = $data['name'];

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO groups (name) VALUES (:name)");

        try {
            $stmt->bindParam(':name', $groupName);  // replace :name with groupName in above query
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { 
                // group already exists error
                return ResponseHelper::createJsonResponse($response, 409);
            } else {
                // internal error
                return ResponseHelper::createJsonResponse($response, 500);
            }
        }

        // group created successfully
        return ResponseHelper::createJsonResponse($response, 201);
    }

    public function joinGroup($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $groupName = $data['groupname'];

        $db = Database::getInstance()->getConnection();

        try {
            // obtain user id using the username
            $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // user not found error
                return ResponseHelper::createJsonResponse($response, 404);
            }
            $userId = $user['id'];

            // use group name to obtain groupId
            $stmt = $db->prepare("SELECT id FROM groups WHERE name = :group_name");
            $stmt->bindParam(':group_name', $groupName);
            $stmt->execute();
            $group = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$group) {
                // group not found error
                return ResponseHelper::createJsonResponse($response, 404);
            }
            $groupId = $group['id'];

            // insert in user groups table
            $stmt = $db->prepare("INSERT INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':group_id', $groupId);
            $stmt->execute();


            // user successfully joined group
            return ResponseHelper::createJsonResponse($response, 201);
        } 
        catch (Exception $e) {
            if ($e->getCode() === '23000') { 
                // user already exists in group error
                return ResponseHelper::createJsonResponse($response, 409);
            } else {
                return ResponseHelper::createJsonResponse($response, 400);
            }
        }
    }

}
