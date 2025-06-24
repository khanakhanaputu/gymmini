<?php

    require_once __DIR__.'./../models/users.model.php';

    function auth($identity, $password, $conn) {
        $user = findUserByIdentity($identity, $conn);

        if($user && password_verify($password, $user['password'])){
            return $user;
        }

        return false;
    }

?>