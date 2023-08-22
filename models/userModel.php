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
    function getAllUsers()
    {
        $users = pg_query($GLOBALS['db'], "SELECT * FROM users");
        return pg_fetch_all($users);
    }

    function getUserById($id)
    {
        $id = $this->getSafeValue($id);
        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE id = $id");
        return pg_fetch_all($user);
    }

    function getUserByEmail($email)
    {
        $email = $this->getSafeValue($email);
        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");
        return pg_fetch_all($user);
    }

    function checkUserExists($email)
    {
        $email = $this->getSafeValue($email);
        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");
        if (pg_num_rows($user) > 0) {
            return true;
        } else {
            return false;
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

        $user = pg_query($GLOBALS['db'], "INSERT INTO users (email, password, name, email_verification_hash, email_verification_expiry) VALUES ('$email', '$password', '$name', '$email_verification_hash', '$email_verification_expiry')");
        pg_fetch_all($user);
        return array(
            'email_verification_hash' => $email_verification_hash,
            'statusCode' => '201'
        );
    }

    function updateUser($id, $email, $password, $name)
    {
        $id = $this->getSafeValue($id);
        $email = $this->getSafeValue($email);
        $password = $this->getSafeValue($password);
        $name = $this->getSafeValue($name);
        $user = pg_query($GLOBALS['db'], "UPDATE users SET email = '$email', password = '$password', name = '$name' WHERE id = $id");
        return pg_fetch_all($user);
    }

    function deleteUser($id)
    {
        $id = $this->getSafeValue($id);
        $user = pg_query($GLOBALS['db'], "DELETE FROM users WHERE id = $id");
        return pg_fetch_all($user);
    }

    function validateUser($email, $password)
    {
        $email = $this->getSafeValue($email);
        $password = $this->getSafeValue($password);

        // checking if values are empty
        if (empty($email) || empty($password)) {
            return "Please fill in all fields";
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