<?php

require_once 'include/header.php';

// check session exists
if (!isset($_SESSION['user'])) {
    header('location: login.php');
}

// get order details
require_once 'controllers/orderController.php';
$orderController = new OrderController();
$result = $orderController->getOrderById($_GET['id']);
?>

<?php if ($result['statusCode'] != 200) { ?>
    <div class="flex justify-center item-center h-screen text-2xl text-red-800">
        <?php echo $result['error']; ?>
    </div>
<?php } else { ?>
    <div class="bg-gray-100 p-2">

        <div class="mx-auto">
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold mb-4">Order Details</h1>
                <p class="text-2xl font-bold mb-4">Total:
                    <?php echo $result['order_info']['price']; ?>
                </p>
            </div>
            <table class="w-full border">
                <thead class="bg-gray-800 text-gray-100">
                    <tr>
                        <th class="border p-2">Product Name</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['order'] as $item) { ?>
                        <tr class="bg-white">
                            <td class="border p-2">
                                <div class="flex justify-center">
                                    <?php echo $item['title']; ?>
                                </div>
                            </td>
                            <td class="border p-2">
                                <div class="flex justify-center">
                                    <?php echo $item['quantity']; ?>
                                </div>
                            </td>
                            <td class="border p-2">
                                <div class="flex justify-center">
                                    <?php echo $item['price']; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- display address of the user -->
            <div class="flex justify-between">
                <div>
                    <div class="p-4 ">
                        <h1 class="text-2xl font-bold my-4">Address</h1>
                        <p class="mb-2">
                            <?php echo $result['address']['line1']; ?>,
                            <?php echo $result['address']['line2']; ?>,
                        </p>
                        <p class="text-gray-600 mb-1">
                            <?php echo $result['address']['district']; ?>,
                        </p>
                        <p class="text-gray-600 mb-1">
                            <?php echo $result['address']['state']; ?>,
                            <?php echo $result['address']['pincode']; ?>
                        </p>
                    </div>
                </div>
                <div>
                    <div class="p-4 ">
                        <h1 class="text-2xl font-bold my-4">User Details</h1>
                        <p class="mb-2">
                            <?php echo $result['user']['name']; ?>
                        </p>
                        <p class="text-gray-600 mb-1">
                            <?php echo $result['user']['email']; ?>
                        </p>
                    </div>
                </div>
            </div>

            <?php // check if session user is admin or not
                if ($_SESSION['user']['admin'] == 't') { ?>
                <div class="flex justify-center">
                    <div>
                        <h1 class="text-2xl font-bold my-4">Change Status</h1>
                        <form action="controllers/orderController.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <select name="status" class="border p-2 mb-2">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button type="submit" name="changeStatus" class="bg-blue-500 text-white p-2">Change Status</button>
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <div class="flex justify-center ">
                    <div>
                        <h1 class="text-2xl font-bold my-4">Status</h1>
                        <p class="mb-2">
                            <?php echo $result['order_info']['status']; ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php } ?>

<?php
require_once 'include/footer.php';
?>