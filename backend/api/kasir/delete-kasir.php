<?php

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-ALlow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers");

    //require
    require_once __DIR__ . './../../includes/connection.php';
    require_once __DIR__ . './../../models/users.model.php';
    require_once __DIR__ . './../../includes/validator.php';

    // buat response
    $response = [
        'status' => null,
        'message' => '',
        'data' => null,
        'errors' => null
    ];


    if($_SERVER["REQUEST_METHOD"] == "DELETE"){

        $id = $_GET["id"] ?? null;

        if(!$id) {
            http_response_code(400); // Bad request
            $response['status'] = 400;
            $response['message'] = "Invalid ID";
            $response['errors'] = "Invalid ID";
        } else {
            // soft delete
            if(deleteUser($id, $conn)) {
                http_response_code(200);
                $response["status"] = 200;
                $response["message"] = "Data deleted";
            } else {
                http_response_code(500);
                $response["status"] = 500;
                $response["message"] = "Internal Server Error";
            }
        }


    } else { 
         // JIKA PAKAI METHOD SELAIN PATCH
        http_response_code(405);
        $response['status'] = 405;
        $response['message'] = "Method not";
    }


    echo json_encode($response);

?>
