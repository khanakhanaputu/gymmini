<?php

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-ALlow-Methods: PATCH");
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


    if($_SERVER["REQUEST_METHOD"] == "PATCH"){

        $jsonInputRaw = file_get_contents('php://input');
        $userDatatoUpdate = json_decode($jsonInputRaw, true);

        if($userDatatoUpdate['role'] !== 'kasir'){
            http_response_code(400);
            $response['status'] = 400;
            $response['message'] = "Bad Request";
            $response['errors'] = "Invalid role!";
            } else {
                if(json_last_error() !== JSON_ERROR_NONE && $userDatatoUpdate !== null) {
                http_response_code(400);
                $response['status'] = 400;
                $response['message'] = "invalid JSON format!";
                $response['errors'] = "invalid JSON format!";

            } else {

                $validationError = updateUserValidation($userDatatoUpdate, $conn);

                if(!empty($validationError)) {
        
                    http_response_code(400);
                    $response['status'] = 400;
                    $response['message'] = "Data input tidak valid!";
                    $response['errors'] = $validationError;
                } else {
        
                    $sanitizedData = [];
                    $sanitizedData['email'] = sanitizeEmail($userDatatoUpdate['email']);
                    $sanitizedData['username'] = sanitizeString($userDatatoUpdate['username']);
                    $sanitizedData['password'] = sanitizeString($userDatatoUpdate['password']);
                    $sanitizedData['password'] = password_hash($sanitizedData['password'], PASSWORD_DEFAULT);
                    

                    $isUpdated = patchUser($sanitizedData, $conn);

                    if($isUpdated) {
                        http_response_code(200);
                        $response['status'] = 200;
                        $response['message'] = "OK";
                        $response['data'] = $sanitizedData;
                    }
                }
            }
        }     

    } else { 
         // JIKA PAKAI METHOD SELAIN PATCH
        http_response_code(405);
        $response['status'] = 405;
        $response['message'] = "Method not allowed";
    }


    echo json_encode($response);

?>
