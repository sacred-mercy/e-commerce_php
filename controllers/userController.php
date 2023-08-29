<?php

require_once 'models\userModel.php';
require_once 'smtp\smtpMailer.php';

class UserController
{
    function validateUser($email, $password)
    {
        $userModel = new UserModel();
        $user = $userModel->validateUser($email, $password);
        return $user;
    }

    function resetPassword($token, $password)
    {
        $userModel = new UserModel();
        // check if the token is valid
        $isValid = $userModel->checkToken($token);
        if ($isValid['statusCode'] === '200') {
            if (!$isValid['valid']) {
                return array(
                    'error' => 'Invalid token',
                    'statusCode' => '400'
                );
            }
        } else {
            return array(
                'error' => $isValid['error'],
                'statusCode' => '400'
            );
        }

        // check if token has expired
        $isExpired = $userModel->checkTokenExpired($token);
        if ($isExpired['statusCode'] === '200') {
            if ($isExpired['expired']) {
                return array(
                    'error' => 'Token has expired',
                    'statusCode' => '400'
                );
            }
        } else {
            return array(
                'error' => $isExpired['error'],
                'statusCode' => '400'
            );
        }

        // reset password
        $result = $userModel->resetPassword($token, $password);

        if ($result['statusCode'] === '200') {
            $email = $userModel->getEmailByToken($token);
            $subject = "Password Reset";
            $msg = "Your password has been reset successfully";
            smtp_mailer($email, $subject, $msg);    
        }

        // generate new token for the user
        $userModel->generateToken($email);

        return $result;
    }

    function sendVerificationCode($email)
    {
        $userModel = new UserModel();
        $user = $userModel->sendVerificationCode($email);
        if ($user['statusCode'] === '200') {
            $token = $user['email_verification_hash'];
            $subject = "Password Reset";
            // get project url path
            $path = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $msg = "Click <a href='http://$path/resetPassword.php?token=$token'>here</a> to reset your password";
            smtp_mailer($email, $subject, $msg);
        }
        return $user;
    }

    function createUser($name, $email, $password)
    {
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
            smtp_mailer($email, $subject, $msg);
        }
        return $user;
    }

    function verifyEmail($email, $token)
    {
        $userModel = new UserModel();
        $user = $userModel->verifyEmail($email, $token);
        return $user;
    }
}