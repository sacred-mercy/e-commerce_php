<?php

require_once 'models\userModel.php';
require_once 'smtp\smtpMailer.php';

class UserController {
    function validateUser($email, $password) {
        $userModel = new UserModel();
        $user = $userModel->validateUser($email, $password);
        return $user;
    }

    function createUser($name, $email, $password) {
        $userModel = new UserModel();
        // check if user exists
        if ($userModel->checkUserExists($email)['statusCode'] === '200') {
            if ($userModel->checkUserExists($email)['exists']) {
                return array(
                    'error' => 'User already exists',
                    'statusCode' => '400'
                );
            }
        } else {
            return array(
                'error' => $userModel->checkUserExists($email)['error'],
                'statusCode' => '400'
            );
        }
        $user = $userModel->createUser($name, $email, $password);
        if ($user['statusCode'] === '201') {
            $token = $user['email_verification_hash'];
            $subject = "Email Verification";
            // get project url path
            $path = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $msg = "Click <a href='http://$path/emailVerify.php?email=$email&token=$token'>here</a> to verify your email";
            smtp_mailer($email, $subject, $msg, null, null);
        } 
        return $user;
    }

    function verifyEmail($email, $token) {
        $userModel = new UserModel();
        $user = $userModel->verifyEmail($email, $token);
        return $user;
    }
}