<?php

    require_once __DIR__ . './../../includes/connection.php';
    require_once __DIR__ . './../../models/users.model.php';
    require_once __DIR__ . './../../includes/auth.php';
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

    
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        
        // amvil data dari body 
        $inputRawJson = file_get_contents('php://input');
        // ubah data menjadi array assosiatif
        $userData = json_decode($inputRawJson, true);

        //cek format json
        if(json_last_error() !== JSON_ERROR_NONE && $userData !== null) {
            http_response_code(400);
            $response['status'] = 400;
            $response['message'] = "Invalid JSON format!";
            $response['errors'] = "Invalid JSON format!";
        } else {

            //cek validasi
            $validationError = loginValidation($userData, $conn);

            //jika ada validasi yang error
            if(!$validationError) {
                http_response_code(400);
                $response['status'] = 400;
                $response['message'] = "Bad request";
                $response['errors'] = $validationError;
            } else {

                // cek apa yang dipakai login
                $identity = detectLoginInputType($userData['identity']);
                
                // sanitasi
                $sanitizeData = [];
                if($identity == 'username' ) {
                    $sanitizeData['identity'] = sanitizeString($userData['identity']);
                } else {
                    $sanitizeData['identity'] = sanitizeEmail($userData['identity']);
                }
                $sanitizeData['password'] = sanitizeString($userData['password']);

                //login
                $result = auth($sanitizeData['identity'], $sanitizeData['password'], $conn);
                if($result) {
                    //jika berhasil kirim response berupa data kecuali password
                    unset($result['password']);
                    http_response_code(200);
                    $response['status'] = 200;
                    $response['message'] = "Login successfuly!";
                    $response['data'] = $result;
                } else {
                    // jika gagal kirim 404z`
                    http_response_code(404);
                    $response['status'] = 404;
                    $response['message'] = "Not Found";
                }
            

            }


        }

    } else {
        http_response_code(405);
        $response['status'] = 405;
        $response['message'] = "Method not allowed!";
    }

    echo json_encode($response);

?>