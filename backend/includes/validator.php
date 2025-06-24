<?php

    require_once __DIR__ . './../models/users.model.php';


    function sanitizeString($str){
        if($str === null){
            return '';
        }
        $str = trim((string)$str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    function sanitizeNumber($number) {
        if($number === null){
            return '';
        }

        return filter_var(trim((string)$number), FILTER_SANITIZE_NUMBER_INT);
    }

    function sanitizeEmail($email) {
        if($email === null){
            return '';
        }

        return filter_var(trim((string)$email), FILTER_SANITIZE_EMAIL);
    }

    function detectLoginInputType($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    function isEmailValid($email){
        if($email === null){
            return '';
        }

        return filter_var(trim((string)$email, FILTER_VALIDATE_EMAIL));
    }

    function isValidName($name){
        if(!preg_match("/^[A-Za-z' -]*$/", $nama)){
            return false;
        }
        return true;
    }

    function isValidUsername($username) {
        if(!preg_match("/^[A-Za-z1-0_]*$/", $username)){
            return false;
        }
        return true;
    }

    function isNumeric($number) {
        return ctype_digit((string)$number);
    }

    function isRequired($value){
        return !empty($value);
    }

    function hasMinLength($value, $min) {
        return strlen(trim((string)$value)) >= $min;
    }

    function hasMaxLength($value, $max) {
        return strlen(trim((string)$value)) <= $max;
    }

    function RegisterValidation($data, $conn) {

        $error = null;

        

        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $role = $data['role'] ?? null;

        // validasi email
        if(!isRequired($email)) {
            $error['email'][] = "Email can't be empty!";
        } else {
            if(!isEmailValid($email)){
                $error['email'][] = "Invalid email format!";
            } elseif(isEmailTaken($email, $conn)) {
                $error['email'][] = "Email already is use";
            }
        }

        // validasi username
        if(!isRequired($username)){
            $error['username'][] = "Username can't be empty!"; 
        } elseif(!hasMinLength($username, 5)) {
            $error['username'][] = "Username must have at least 5 characters";
        } elseif(!hasMaxLength($username, 20)) {
            $error['username'][] = "username has a maximum of 20 characters";
        } elseif(isUsernameTaken($username, $conn)){
            $error['username'][] = "username already taken";
        }
        
        // validasi password
        if(!isRequired($password)){
            $error['password'][] = "password can't be empty!"; 
        } elseif(!hasMinLength($password, 8)) {
            $error['password'][] = "password must have at least 8 characters";
        } elseif(!hasMaxLength($password, 16)) {
            $error['password'][] = "password has a maximum of 16 characters";
        }

        //validasi role
        if(!isRequired($role)){
            $error['role'][] = "role can't be empty!";
        }

        return $error;
    }

    function loginValidation($data, $conn) {
        $error = null;

        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // validasi email
        if(!isRequired($email)) {
            $error['email'][] = "Email can't be empty!";
        } else {
            if(!isEmailValid($email)){
                $error['email'][] = "Invalid email format!";
            } elseif(isEmailTaken($email, $conn)) {
                $error['email'][] = "Email already is use";
            }
        }

        // validasi username
        if(!isRequired($username)){
            $error['username'][] = "Username can't be empty!"; 
        } elseif(!hasMinLength($username, 5)) {
            $error['username'][] = "Username must have at least 5 characters";
        } elseif(!hasMaxLength($username, 20)) {
            $error['username'][] = "username has a maximum of 20 characters";
        } elseif(isUsernameTaken($username, $conn)){
            $error['username'][] = "username already taken";
        }
        
        // validasi password
        if(!isRequired($password)){
            $error['password'][] = "password can't be empty!"; 
        } elseif(!hasMinLength($password, 8)) {
            $error['password'][] = "password must have at least 8 characters";
        } elseif(!hasMaxLength($password, 16)) {
            $error['password'][] = "password has a maximum of 16 characters";
        }

        return $error;

    }

?>