<?php


require_once 'ResponseHelper.php';

class MessageController {
    public function sendMessage($request, $response, $args) {
        $data = $request->getParsedBody();
        $username = trim($data['user_name']);
        $groupName = trim($data['group_name']);
        $content = $data['content'];

        $db = Database::getInstance()->getConnection();

        try {
            // checking if username exists
            $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // user does not exist error
                return ResponseHelper::createJsonResponse($response, 404);
            }

            // checking if group exists
            $stmt = $db->prepare("SELECT id FROM groups WHERE name = :group_name");
            $stmt->bindParam(':group_name', $groupName);
            $stmt->execute();
            $group = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$group) {
            // group does not exist error
                return ResponseHelper::createJsonResponse($response, 404);
            }

            // insert message in messages table
            $stmt = $db->prepare("INSERT INTO messages (group_name, user_name, content) VALUES (:group_id, :user_id, :content)");
            $stmt->bindParam(':group_id', $groupName);
            $stmt->bindParam(':user_id', $username);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            return ResponseHelper::createJsonResponse($response, 201);
        } 
        catch (Exception $e) {
            // internal error
            return ResponseHelper::createJsonResponse($response, 400);
        }
    }


    public function listMessages($request, $response, $args) {
        $groupName = $args['group_name'];

        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT id FROM groups WHERE name = :group_name");
        $stmt->bindParam(':group_name', $groupName);
        $stmt->execute();
        $group = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$group) {
            // group does not exist error
            return ResponseHelper::createJsonResponse($response, 404);
        }

        $stmt = $db->prepare("SELECT * FROM messages WHERE group_name = :group_name");
        $stmt->bindParam(':group_name', $groupName);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $payload = json_encode(['messages' => $messages]);
        $response->getBody()->write($payload);
        // return message with 201 response
        return ResponseHelper::createJsonResponse($response, 201);
    }
}
