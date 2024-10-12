<?php


class ResponseHelper {
    // create a JSON response with the specified status code
    public static function createJsonResponse($response, int $statusCode) {
        return $response->withStatus($statusCode)
                        ->withHeader('Content-Type', 'application/json');
    }
}
