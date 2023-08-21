<?php

require '..\config\db.php';

class UserModel
{
    function getSafeValue($value){
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

    function createUser($name, $email, $password)
    {
        $email = $this->getSafeValue($email);
        $password = $this->getSafeValue($password);
        $name = $this->getSafeValue($name);
        $isAdmin = 0;
        $user = pg_query($GLOBALS['db'], "INSERT INTO users (email, password, name, isAdmin) VALUES ('$email', '$password', '$name', CAST($isAdmin AS BOOLEAN))");
        return pg_fetch_all($user);
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
        $user = pg_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        return pg_fetch_all($user);
    }
}