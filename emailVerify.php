<?php

// user controller
require_once 'controllers/userController.php';

error_reporting(E_ERROR | E_PARSE); // hide warnings
// get email and hash from URL
$email = $_GET['email'];
$token = $_GET['token'];

// verify email
$userController = new UserController();
$msz = $userController->verifyEmail($email, $token);

require_once 'include/header.php';
?>

<div class="flex justify-center items-center h-screen">
    <?php if ($msz === "Email verified successfully") { ?>
        <div class="bg-green-500 text-white p-4">
            Email verified successfully. Please
            <a href="login.php" class="underline text-blue-500">Login</a>
        </div>
    <?php } else { ?>
        <div class="bg-red-500 text-white p-4">
            <?php echo $msz; ?>
        </div>
    <?php } ?>
</div>


<?php require_once 'include/footer.php'; ?>