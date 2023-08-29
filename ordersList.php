<?php
require_once 'include/header.php';

// check session exists
if (!isset($_SESSION['user'])) {
    header('location: login.php');
}

require_once 'controllers/orderController.php';
$orderController = new OrderController();

// check for post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $result = $orderController->cancelOrder($_POST['id']);
    if ($result['statusCode'] == 200) {
        header('location: ordersList.php');
    } else {
        echo '<div class="flex justify-center item-center h-screen text-2xl text-red-800">
            Something went wrong!
        </div>';
    }
}

// get all orders
$orders = $orderController->getAllOrders();
?>


<div class="bg-gray-100 p-2">

    <div class="mx-auto">
        <table class="w-full border">
            <thead class="bg-gray-800 text-gray-100">
                <tr>
                    <th class="border p-2">Order ID</th>
                    <th class="border p-2">Order Time</th>
                    <?php if ($_SESSION['user']['admin'] == 't') { ?>
                        <th class="border p-2">status</th>
                    <?php } else { ?>
                        <th class="border p-2">Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders['orders'] as $order) { ?>
                    <tr class="bg-white hover:bg-gray-200 cursor-pointer" title="Click to view more details"
                        onclick="window.location.href='ordersViewDetails.php?id=<?php echo $order['id']; ?>'">
                        <td class="border p-2">
                            <div class="flex justify-center">
                                <?php echo $order['id']; ?>
                            </div>
                        </td>
                        <td class="border p-2">
                            <div class="flex justify-center">
                                <?php echo $order['date_time']; ?>
                            </div>
                        </td>

                        <?php if ($_SESSION['user']['admin'] == 't') { ?>
                            <td class="border p-2">
                                <div class="flex justify-center">
                                    <?php echo $order['status']; ?>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td class="border p-2">
                                <div class="flex justify-center">
                                    <?php if ($order['status'] != 'cancelled' && $order['status'] != 'completed') { ?>
                                        <form action="ordersList.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Cancel
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <button
                                            class="bg-gray-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed"
                                            disabled>
                                            <?php echo $order['status']; ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>