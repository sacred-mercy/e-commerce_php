<?php 
require_once '../controllers/userController.php';

$errMsz = '';
$name = '';
$email = '';
$password = '';

if (isset($_SESSION['user'])) {
    header('Location: ./'); // redirect to home page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userController = new UserController();
    $user = $userController->createUser($name, $email, $password);
    if ($user) {
        header('Location: login.php');
    } else {
        $errMsz = 'Invalid email or password';
    }
}

?>

<?php require_once 'partials/header.php'; ?>

<div class="constainer flex justify-center items-center h-screen">
    <div>
        <form action="signUp.php" method="POST">
            <div class="mb-4">
                <label for="name" class="sr-only">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name"
                    class="bg-gray-100 border-2 w-64 p-4 rounded-lg" value="<?php echo $name; ?>" />
            </div>
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
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-3 mb-3 rounded font-medium w-64">
                    Register
                </button>
            </div>
        </form>
        <a href="login.php">Already have an account?
            <button class="border rounded p-2 bg-slate-200 font-bold">
                Login
            </button>
        </a>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>