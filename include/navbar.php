<nav class="flex justify-between bg-gray-900 text-white w-screen">
    <div class="px-5 xl:px-4 py-1 flex w-full items-center">
        <a class="text-3xl font-bold font-heading" href="index.php"> E-Commerce </a>
        <!-- Nav Links -->
        <ul class="flex px-4 mx-auto font-semibold font-heading space-x-12">
            <li><a class="hover:text-gray-200" href="index.php">Home</a></li>
            <?php if (isset($_SESSION['user'])) { ?>
                <?php if ($_SESSION['user']['admin'] == 't') { ?>
                    <li><a class="hover:text-gray-200" href="adminDashboard.php">Dashboard</a></li>
                    <li><a class="hover:text-gray-200" href="ordersList.php">Orders</a></li>
                    <li><a class="hover:text-gray-200" href="adminProductsView.php">Products</a></li>
                <?php } else { ?>
                    <li><a class="hover:text-gray-200" href="cart.php">Cart</a></li>
                    <li><a class="hover:text-gray-200" href="ordersList.php">Orders</a></li>
                <?php } ?>
            </ul>
            <a href="logout.php" class="text-white bg-red-600 border rounded p-1 hover:text-gray-200">Logout</a>
        <?php } else { ?>
            </ul>
            <a href="login.php" class="text-white bg-green-600 border rounded p-1 hover:text-gray-200">Login</a>
        <?php } ?>
    </div>
</nav>