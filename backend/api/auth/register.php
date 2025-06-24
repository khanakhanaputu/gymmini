<?php

    require_once __DIR__ . './../../includes/connection.php';
    require_once __DIR__ . './../../models/users.model.php';
    require_once __DIR__ . './../../includes/validator.php';

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers");

    $response = [
        'status' => null,
        'message' => '',
        'data' => null,
        'errors' => null
    ];

    // $dummmyData = [
    //     'email' => 'nirmalacantik@gmail.com',
    //     'username' => 'nirmala',
    //     'password' => '12345678',
    //     'role' => 'member'
    // ];

    
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        // ambil data json dari body
        $jsonInputRaw = file_get_contents('php://input');
        $userData = json_decode($jsonInputRaw, true);

        //cek json 
        if(json_last_error() !== JSON_ERROR_NONE && $userData !== null) {
            http_response_code(400);
            $response['status'] = 400;
            $response['message'] = "invalid JSON format!";
            $response['errors'] = "invalid JSON format!";
        } else {
            //validasi
            $validationError = RegisterValidation($userData, $conn);

            
            
            if(!empty($validationError)){
                http_response_code(400);
                $response['status'] = 400;
                $response['message'] = "Bad request!";
                $response['errors'] = $validationError;
            } else {
                //sanitasi
                $sanitizedData = [];
                $sanitizedData['email'] = sanitizeEmail($userData['email']);
                $sanitizedData['username'] = sanitizeString($userData['username']);
                $sanitizedData['password'] = sanitizeString($userData['password']);
                $sanitizedData['role'] = sanitizeString($userData['role']);
                
                //hash password
                $sanitizedData['password'] = password_hash($sanitizedData['password'], PASSWORD_DEFAULT);

                // insert to database
                $result = createDataUsers($sanitizedData, $conn);
                if($result){
                    unset($sanitizedData['password']);
                    $response['status'] = 201;
                    $response['message'] = 'Created!';
                    $response['data'] = $sanitizedData;
                }

            }

        }
        
        
    } else { //method selain post
        http_response_code(405);
        $response['status'] = 405;
        $response['message'] = "Method not allowed!";
    }

    echo json_encode($response);

?>