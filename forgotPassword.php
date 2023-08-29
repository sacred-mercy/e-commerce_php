<?php

require_once 'include/header.php';
require_once 'controllers/userController.php';

$errMsz = '';
$email = '';

$verificationLinkSent = false;

if (isset($_SESSION['user'])) {
    header('Location: index.php'); // redirect to home page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $userController = new UserController();
    $result = $userController->sendVerificationCode($email);
    if ($result['statusCode'] == 200) {
        $verificationLinkSent = true;
        $errMsz = $result['message'];
    } else {
        $errMsz = $result['error'];
    }
}

if ($verificationLinkSent === true) {
    ?>
    <div class="constainer flex justify-center items-center h-screen">
        <div>
            <h1 class="text-2xl font-bold mb-4">Verification Link Sent</h1>
            <p class="text-center">
                A verification link has been sent to your email. Please click on the link to reset your
                password.
            </p>
        </div>
    </div>

    <?php
} else {
    ?>

    <div class="constainer flex justify-center items-center h-screen">
        <div>
            <form action="forgotPassword.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email"
                        class="bg-gray-100 border-2 w-64 p-4 rounded-lg" value="<?php echo $email; ?>" />
                </div>

                <!-- display error message -->
                <div class="text-red-500 mb-4 text-center">
                    <?php echo $errMsz; ?>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-3 mb-3 rounded font-medium w-64">
                        Send Verification Link
                    </button>
                </div>
            </form>
            <a href="login.php">Don't have an account?
                <button class="border rounded p-2 bg-slate-200 font-bold">Go back</button>
            </a>
        </div>
    </div>

    <?php
}
require_once 'include/footer.php'; ?>