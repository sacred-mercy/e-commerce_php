<?php
require_once 'include/header.php';

// get all orders
require_once 'controllers/orderController.php';
$orderController = new OrderController();
$orders = $orderController->getAllOrders();
?>


<div class="bg-gray-100 p-2">

    <div class="mx-auto">
        <table class="w-full border">
            <thead class="bg-gray-800 text-gray-100">
                <tr>
                    <th class="border p-2">Order ID</th>
                    <th class="border p-2">status</th>
                    <th class="border p-2">Order Time</th>
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
                                <?php echo $order['status']; ?>
                            </div>
                        </td>
                        <td class="border p-2">
                            <div class="flex justify-center">
                                <?php echo $order['date_time']; ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>