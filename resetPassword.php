<?php

require_once 'include/header.php';
require_once 'controllers/userController.php';

$token = $_GET['token'];

$errMsz = '';

if (isset($_SESSION['user'])) {
    header('Location: index.php'); // redirect to home page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    if ($password === $confirmPassword) {
        $userController = new UserController();
        $result = $userController->resetPassword($token, $password);
        if ($result['statusCode'] == 200) {
            header('Location: login.php');
        } else {
            $errMsz = $result['error'];
        }
    } else {
        $errMsz = 'Passwords do not match';
    }
}

?>

<?php
if ($token == '') {
    header('Location: login.php');
}
?>

<div class="constainer flex justify-center items-center h-screen">
    <div>
        <form action="resetPassword.php?token=<?php echo $token; ?>" method="POST">
            <div class="mb-4">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password"
                    class="bg-gray-100 border-2 w-64 p-4 rounded-lg" />
            </div>

            <div class="mb-4">
                <label for="confirmPassword" class="sr-only">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword"
                    placeholder="Confirm your password" class="bg-gray-100 border-2 w-64 p-4 rounded-lg" />
            </div>

            <!-- display error message -->
            <div class="text-red-500 mb-4 text-center">
                <?php echo $errMsz; ?>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-3 mb-3 rounded font-medium w-64">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'include/footer.php';
?>