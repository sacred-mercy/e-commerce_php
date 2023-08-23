<?php

require 'config\db.php';

class UserModel
{
    function getSafeValue($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    function checkUserExists($email)
    {
        $email = $this->getSafeValue($email);
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
        $email = $this->getSafeValue($email);
        $password = $this->getSafeValue($password);
        $name = $this->getSafeValue($name);

        // checking if values are empty
        if (empty($email) || empty($password) || empty($name)) {
            return array(
                'error' => 'Please fill in all fields',
                'statusCode' => '400'
            );
        }

        // email verification hash with time to make it unique
        $email_verification_hash = md5($email . time());

        // hashing password with salt
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
        $email = $this->getSafeValue($email);
        $password = $this->getSafeValue($password);

        // checking if values are empty
        if (empty($email) || empty($password)) {
            return "Please fill in all fields";
        }

        // checking if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email";
        }

        // check if user exists
        if (!$this->checkUserExists($email)) {
            return "User does not exist";
        }

        // checking verification status
        $email_verification_status = pg_query($GLOBALS['db'], "SELECT isverified FROM users WHERE email = '$email'");
        $email_verification_status = pg_fetch_all($email_verification_status)[0]['isverified'];

        if ($email_verification_status == 'f') {
            return "Please verify your email";
        }


        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");

        $user = pg_fetch_all($user);

        // checking password
        $isCorrect = password_verify($password, $user[0]['password']);
        if (!$isCorrect) {
            return "Invalid Password";
        }

        $user = $user[0];
        $user = array(
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        );
        return $user;
    }

    function verifyEmail($email, $token)
    {
        $email = $this->getSafeValue($email);
        $token = $this->getSafeValue($token);

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