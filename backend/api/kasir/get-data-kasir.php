<?php

    require_once __DIR__ . './../../includes/connection.php';
    require_once __DIR__ . './../../models/users.model.php';
    require_once __DIR__ . './../../includes/validator.php';

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers");

    $response = [
        'status' => null,
        'message' => '',
        'data' => null,
        'errors' => null
    ];

    if($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ?? null;


        // get data by id
        if($id) {
            $kasirDataById = getDataKasirById($id, $conn);
            if($kasirDataById) {
                unset($kasirDataById['password']);
                http_response_code(200);
                $response['status'] = 200;
                $response['message'] = 'OK';
                $response['data'] = $kasirDataById;
            } else {
                unset($kasirDataById['password']);
                http_response_code(404); // 404 Not Found
                $respone['status'] = 404;
                $respone['message'] = "Data tidak ditemukan";
                $respone['data'] = [];
            }
        
        // get all data
        } else {
            $allKasirData = getAllDataKasir($conn);
            if($allKasirData) {
                unset($allKasirData['password']);
                http_response_code(200);
                $response['status'] = 200;
                $response['message'] = 'OK';
                $response['data'] = $allKasirData;
            } else {
                unset($allKasirData['password']);
                http_response_code(200); // 200 Not Found
                $respone['status'] = 200;
                $respone['message'] = "Tidak ada tidak ditemukan";
                $respone['data'] = [];
            }
        }
    } else { //method selain post
        http_response_code(405);
        $response['status'] = 405;
        $response['message'] = "Method not allowed!";
    }

    echo json_encode($response);

?>