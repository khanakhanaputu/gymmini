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

    function findUserByIdentity($identity, $conn) {
        $query = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $identity, $identity);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $isAvailable = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $isAvailable;
    }


?>