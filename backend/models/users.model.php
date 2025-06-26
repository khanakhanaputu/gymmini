<?php

    function createDataUsers($data, $conn){
        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $role = $data['role'] ?? null;

        $query = "INSERT INTO users(email, username, password, role) VALUES(?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $email, $username, $password, $role);

        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    
    function isEmailTaken($email, $conn) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        
        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt); 
        $isTaken = mysqli_stmt_num_rows($stmt) > 0;

        mysqli_stmt_close($stmt);
        return $isTaken;
    }

    function isUsernameTaken($username, $conn) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        
        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt); 
        $isTaken = mysqli_stmt_num_rows($stmt) > 0;

        mysqli_stmt_close($stmt);
        return $isTaken;
    }

    function isEmailTakenByOther($email, $id, $conn) {
        $query = "SELECT * FROM users WHERE email = ? AND users.user_id != ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $email, $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);


        if($result && mysqli_num_rows($result) > 0) {
            return true;
        }

        mysqli_stmt_close($stmt);
        return false;
    }

    function isUsernameTakenByOther($username, $id, $conn) {
        $query = "SELECT * FROM users WHERE username = ? AND users.user_id != ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $username, $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

       
        if($result && mysqli_num_rows($result) > 0) {
            return true;
        }

        mysqli_stmt_close($stmt);
        return false;
    }

    function findUserByIdentity($identity, $conn) {
        $query = "SELECT user_id, email, username, role, users_status FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $identity, $identity);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $isAvailable = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $isAvailable;
    }

    function getAllDataKasir($conn) {
        $query = "SELECT user_id, email, username, role, users_status FROM users WHERE users.role = 'kasir'";
        $result = mysqli_query($conn, $query);

        $data_kasir = [];
        if($result && mysqli_num_rows($result) > 0){
            while($rows = mysqli_fetch_assoc($result)){
                $data_kasir[] = $rows;
            }
        }

        return $data_kasir;
    }

    function getDataKasirById($id, $conn) {
        $query = "SELECT * FROM users WHERE users.role = 'kasir' AND users.user_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $dataKasirById = null;
        if($result && mysqli_num_rows($result) > 0){
            $dataKasirById = mysqli_fetch_assoc($result);
        }

        return $dataKasirById;
    }

    function patchUser($data, $conn) {

        $email = $data["email"] ?? null;
        $username = $data["username"] ?? null;
        $password = $data["password]"] ?? null;
        $user_id = $data["user_id]"] ?? null;

        $query = "UPDATE users SET users.email = ?,
                                   users.username = ?,
                                   users.password = ? 
                                   WHERE users.user_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi",  $email, $username, $password, $user_id);
         if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }

    }

    function deleteUser($id, $conn) {

        $query = "UPDATE users SET users.users_status = 0 WHERE users.user_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i",  $id);
         if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }


?>