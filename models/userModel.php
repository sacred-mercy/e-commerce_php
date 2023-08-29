<?php

require 'config\db.php';
require_once 'functions\getSafeValue.php';

class UserModel
{
    function passwordValidations($password){
        // validating password length
        if (strlen($password) < 8) {
            return array(
                'error' => 'Password must be atleast 6 characters long',
                'statusCode' => '400'
            );
        }

        // validating password strength for a capital letter, a small letter, a number and a special character
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z\d]/', $password)) {
            return array(
                'error' => 'Password must contain a capital letter, a small letter, a number and a special character',
                'statusCode' => '400'
            );
        }
    }

    function getEmailByToken($token){
        $token = getSafeValue($token);
        try {
            $user = pg_query($GLOBALS['db'], "SELECT email FROM users WHERE email_verification_hash = '$token'");
            $user = pg_fetch_all($user);
            return $user[0]['email'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function generateToken($email){
        $email = getSafeValue($email);
        // email verification hash with time to make it unique
        $email_verification_hash = md5($email . time());

        // expiry date set to 5 minutes from now
        $email_verification_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        try {
            $user = pg_query($GLOBALS['db'], "UPDATE users SET email_verification_hash = '$email_verification_hash', email_verification_expiry = '$email_verification_expiry' WHERE email = '$email'");
            if ($user) {
                return array(
                    'message' => 'Verification code sent generated successfully',
                    'email_verification_hash' => $email_verification_hash,
                    'statusCode' => '200'
                );
            } else {
                return array(
                    'error' => 'Something went wrong',
                    'statusCode' => '400'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function checkTokenExpired($token)
    {
        $token = getSafeValue($token);
        try {
            $user = pg_query($GLOBALS['db'], "SELECT email_verification_expiry FROM users WHERE email_verification_hash = '$token'");
            $user = pg_fetch_all($user);

            $expiry = $user[0]['email_verification_expiry'];
            $expiry = strtotime($expiry);
            $now = strtotime(date('Y-m-d H:i:s'));
            if ($expiry > $now) {
                return array(
                    'expired' => false,
                    'statusCode' => '200'
                );
            } else {
                return array(
                    'expired' => true,
                    'statusCode' => '200'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }
    function checkToken($token)
    {
        $token = getSafeValue($token);
        try {
            $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email_verification_hash = '$token'");
            $user = pg_fetch_all($user);
            if (count($user) > 0) {
                return array(
                    'valid' => true,
                    'statusCode' => '200'
                );
            } else {
                return array(
                    'valid' => false,
                    'statusCode' => '200'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function resetPassword($token, $password)
    {
        $token = getSafeValue($token);
        $password = getSafeValue($password);

        // checking if values are empty
        if (empty($token) || empty($password)) {
            return array(
                'error' => 'Please fill in all fields',
                'statusCode' => '400'
            );
        }

        // validating password
        $passwordValidations = $this->passwordValidations($password);
        if($passwordValidations['statusCode'] === '400'){
            // return $passwordValidations;
        }

        // hashing password
        $password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $user = pg_query($GLOBALS['db'], "UPDATE users SET password = '$password' WHERE email_verification_hash = '$token'");
            if ($user) {
                return array(
                    'message' => 'Password reset successfully',
                    'statusCode' => '200'
                );
            } else {
                return array(
                    'error' => 'Something went wrong',
                    'statusCode' => '400'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function sendVerificationCode($email)
    {
        $email = getSafeValue($email);

        // checking if values are empty
        if (empty($email)) {
            return array(
                'error' => 'Please fill in all fields',
                'statusCode' => '400'
            );
        }

        // checking if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'error' => 'Invalid email',
                'statusCode' => '400'
            );
        }

        // check if user exists
        if ($this->checkUserExists($email)['statusCode'] === '200') {
            if (!$this->checkUserExists($email)['exists']) {
                return array(
                    'error' => 'User does not exist',
                    'statusCode' => '400'
                );
            }
        } else {
            return array(
                'error' => $this->checkUserExists($email)['error'],
                'statusCode' => '400'
            );
        }

        // generate token for the user
        $result = $this->generateToken($email);
        return $result;
    }

    function checkUserExists($email)
    {
        $email = getSafeValue($email);
        try {
            $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");
            $user = pg_fetch_all($user);
            if (count($user) > 0) {
                return array(
                    'exists' => true,
                    'statusCode' => '200'
                );
            } else {
                return array(
                    'exists' => false,
                    'statusCode' => '200'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function createUser($name, $email, $password)
    {
        $email = getSafeValue($email);
        $password = getSafeValue($password);
        $name = getSafeValue($name);

        // checking if values are empty
        if (empty($email) || empty($password) || empty($name)) {
            return array(
                'error' => 'Please fill in all fields',
                'statusCode' => '400'
            );
        }

        // validating email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'error' => 'Invalid email',
                'statusCode' => '400'
            );
        }

        //validating password
        $passwordValidations = $this->passwordValidations($password);
        if($passwordValidations['statusCode'] === '400'){
            return $passwordValidations;
        }

        // validating name
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            return array(
                'error' => 'Invalid name',
                'statusCode' => '400'
            );
        }

        // email verification hash with time to make it unique
        $email_verification_hash = md5($email . time());

        // hashing password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // expiry date set to 5 minutes from now
        $email_verification_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        try {
            $user = pg_query($GLOBALS['db'], "INSERT INTO users (name, email, password, email_verification_hash, email_verification_expiry) VALUES ('$name', '$email', '$password', '$email_verification_hash', '$email_verification_expiry')");
            if ($user) {
                return array(
                    'message' => 'User created successfully',
                    'email_verification_hash' => $email_verification_hash,
                    'statusCode' => '201'
                );
            } else {
                return array(
                    'error' => 'Something went wrong',
                    'statusCode' => '400'
                );
            }
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'statusCode' => '400'
            );
        }
    }

    function validateUser($email, $password)
    {
        $email = getSafeValue($email);
        $password = getSafeValue($password);

        // checking if values are empty
        if (empty($email) || empty($password)) {
            return array(
                'error' => 'Please fill in all fields',
                'statusCode' => '400'
            );
        }

        // checking if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'error' => 'Invalid email',
                'statusCode' => '400'
            );
        }

        // check if user exists
        if ($this->checkUserExists($email)['statusCode'] === '200') {
            if (!$this->checkUserExists($email)['exists']) {
                return array(
                    'error' => 'User does not exist',
                    'statusCode' => '400'
                );
            }
        } else {
            return array(
                'error' => $this->checkUserExists($email)['error'],
                'statusCode' => '400'
            );
        }

        // checking verification status
        $email_verification_status = pg_query($GLOBALS['db'], "SELECT isverified FROM users WHERE email = '$email'");
        $email_verification_status = pg_fetch_all($email_verification_status)[0]['isverified'];

        if ($email_verification_status == 'f') {
            return array(
                'error' => 'Email not verified',
                'statusCode' => '400'
            );
        }


        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");

        $user = pg_fetch_all($user);

        // checking password
        $isCorrect = password_verify($password, $user[0]['password']);
        if (!$isCorrect) {
            return array(
                'error' => 'Invalid password',
                'statusCode' => '400'
            );
        }

        $user = $user[0];
        return array(
            'user' => array(
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'admin' => $user['isadmin']
            ),
            'statusCode' => '200'
        );
    }

    function verifyEmail($email, $token)
    {
        $email = getSafeValue($email);
        $token = getSafeValue($token);

        // checking if values are empty
        if (empty($email) || empty($token)) {
            return "Invalid Link";
        }

        // check if user exists
        if (!$this->checkUserExists($email)) {
            return "User does not exist";
        }

        // checking verification status
        $email_verification_status = pg_query($GLOBALS['db'], "SELECT isverified FROM users WHERE email = '$email'");
        $email_verification_status = pg_fetch_all($email_verification_status)[0]['isverified'];

        if ($email_verification_status == 't') {
            return "Email already verified";
        }

        // checking if token is valid
        $email_verification_hash = pg_query($GLOBALS['db'], "SELECT email_verification_hash FROM users WHERE email = '$email'");
        $email_verification_hash = pg_fetch_all($email_verification_hash)[0]['email_verification_hash'];

        // checking if token is valid
        if ($email_verification_hash != $token) {
            return "Invalid token";
        }

        // updating user
        pg_query($GLOBALS['db'], "UPDATE users SET isverified = 't' WHERE email = '$email'");


        return "Email verified successfully";
    }
}