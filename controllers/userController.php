<?php

require_once '..\models\userModel.php';

class UserController {
    function validateUser($email, $password) {
        $userModel = new UserModel();
        $user = $userModel->validateUser($email, $password);
        return $user;
    }

    function createUser($name, $email, $password) {
        $userModel = new UserModel();
        $user = $userModel->createUser($name, $email, $password);
        return $user;
    }
}