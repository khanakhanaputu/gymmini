<?php

    require_once __DIR__ . './../includes/connection.php';


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
        if(!preg_match("/^[A-Za-z' -]*$/", $name)){
            return false;
        }
        return true;
    }

    function isValidUsername($username) {
        if(!preg_match("/^[A-Za-z0-9_]*$/", $username)){
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

    function isValidGender($gender) {
        $genderValid = ['L', 'P'];
        if(!in_array($gender, $genderValid)){
            return false;
        }
        return true;
    }

    function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
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

        $identity = $data['identity'] ?? null;
        $password = $data['password'] ?? null;

        // validasi identity
        if(!isRequired($identity)) {
            $error['identity'][] = "identity can't be empty!";
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

    function continueRegisterValidation($data) {
        $error = null;
        
        $nama = $data["nama"] ?? null;
        $noTelp = $data["noTelp"] ?? null;
        $gender = $data["jk"] ?? null;
        $dateOfBirth = $data["tglLahir"] ?? null;

        //validasi nama
        if(!isRequired($nama)) {
            $error["nama"][] = "Name can't be empty";
        } elseif(!isValidName($nama)) {
            $error["nama"][] = "Name can only contains letters, ', -";
        }

        //validasi noTelp
        if(!isRequired($noTelp)) {
            $error["noTelp"][] = "Phone number can't be empty";
        } elseif(!hasMinLength($noTelp, 10)) {
            $error["noTelp"][] = "Phone number at least have 10 chars";
        } elseif(!hasMaxLength($noTelp, 15)) {
            $error["noTelp"][] = "Phone number maximal have 15 chars";
        }

        //validasi gender 
        if(!isRequired($gender)) {
            $error["gender"][] = "Gender can't be empty";
        } elseif(!isValidGender($gender)) {
            $error["gender"][] = "Invalid gender";
        }
        
        //validasi tanggal lahir
        
        if(!isRequired($dateOfBirth)) {
            $error["date-of-birth"][] = "date of birth can't be empty";
        } elseif(!isValidDate($dateOfBirth)) {
            $error["date-of-birth"][] = "invalid date";

        }

        return $error;

    }

    // validasi update user 
    function updateUserValidation($data, $conn)  {
        $error = null;

        $email = $data["email"] ?? null;
        $username = $data["username"] ?? null;
        $password = $data["password"] ?? null;
        $user_id = $data["user_id"] ?? null;

        //validasi email 
        // validasi email
        if(!isRequired($email)) {
            $error['email'][] = "Email can't be empty!";
        } else {
            if(!isEmailValid($email)){
                $error['email'][] = "Invalid email format!";
            } elseif(isEmailTakenByOther($email, $user_id, $conn)) {
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
        } elseif(isUsernameTakenByOther($username, $user_id, $conn)){
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

    function memberUpdate($data) {
        $error = null;
        
        $nama = $data["nama"] ?? null;
        $noTelp = $data["noTelp"] ?? null;
        $gender = $data["jk"] ?? null;
        $dateOfBirth = $data["tglLahir"] ?? null;
        $memberId = $data["member_id"] ?? null;

        //validasi nama
        if(!isRequired($nama)) {
            $error["nama"][] = "Name can't be empty";
        } elseif(!isValidName($nama)) {
            $error["nama"][] = "Name can only contains letters, ', -";
        }

        //validasi noTelp
        if(!isRequired($noTelp)) {
            $error["noTelp"][] = "Phone number can't be empty";
        } elseif(!hasMinLength($noTelp, 10)) {
            $error["noTelp"][] = "Phone number at least have 10 chars";
        } elseif(!hasMaxLength($noTelp, 15)) {
            $error["noTelp"][] = "Phone number maximal have 15 chars";
        }

        //validasi gender 
        if(!isRequired($gender)) {
            $error["gender"][] = "Gender can't be empty";
        } elseif(!isValidGender($gender)) {
            $error["gender"][] = "Invalid gender";
        }
        
        //validasi tanggal lahir
        
        if(!isRequired($dateOfBirth)) {
            $error["date-of-birth"][] = "date of birth can't be empty";
        } elseif(!isValidDate($dateOfBirth)) {
            $error["date-of-birth"][] = "invalid date";

        }

        if(!isRequired($memberId)) {
            $error["member-id"][] = "date of birth can't be empty";
        } 

        return $error;
    }

?>