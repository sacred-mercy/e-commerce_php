<?php require_once 'include/header.php'; ?>
<?php

require_once 'controllers/userController.php';
$errMsz = '';
$email = '';
$password = '';

if (isset($_SESSION['user'])) {
    header('Location: index.php'); // redirect to home page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userController = new UserController();
    $user = $userController->validateUser($email, $password);
    if (gettype($user) !== 'string') {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit();
    } else {
        $errMsz = $user;
    }
}

?>


<div class="constainer flex justify-center items-center h-screen">
    <div>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email"
                    class="bg-gray-100 border-2 w-64 p-4 rounded-lg" value="<?php echo $email; ?>" />
            </div>
            <div class="mb-4">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password"
                    class="bg-gray-100 border-2 w-64 p-4 rounded-lg" value="<?php echo $password; ?>" />
            </div>

            <!-- display error message -->
            <div class="text-red-500 mb-4 text-center">
                <?php echo $errMsz; ?>
            </div>

            <!-- Forget Password link -->
            <div class="mb-4">
                <a href="/forgotPassword" class="text-blue-500">Forget Password?</a>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-3 mb-3 rounded font-medium w-64">
                    Login
                </button>
            </div>
        </form>
        <a href="signUp.php">Don't have an account?
            <button class="border rounded p-2 bg-slate-200 font-bold">Register</button>
        </a>
    </div>
</div>

<?php require_once 'include/footer.php'; ?>