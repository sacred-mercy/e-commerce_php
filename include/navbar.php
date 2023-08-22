<nav class="flex justify-between bg-gray-900 text-white w-screen">
    <div class="px-5 xl:px-4 py-1 flex w-full items-center">
        <a class="text-3xl font-bold font-heading" href="index.php"> E-Commerce </a>
        <!-- Nav Links -->
        <ul class="hidden md:flex px-4 mx-auto font-semibold font-heading space-x-12">
            <li><a class="hover:text-gray-200" href="index.php">Home</a></li>
        </ul>
        <?php if (isset($_SESSION['user'])) { ?>
            <p>
                <span class="text-gray-200 mx-2">Welcome
                    <?php echo $_SESSION['user']['name']; ?>
                </span>
            </p>
            <a href="logout.php" class="text-white bg-red-600 border rounded p-1 hover:text-gray-200">Logout</a>
        <?php } else { ?>
            <a href="login.php" class="text-white bg-green-600 border rounded p-1 hover:text-gray-200">Login</a>
        <?php } ?>
    </div>
</nav>