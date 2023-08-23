<?php
require_once 'include/header.php';

// check if session is set
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

require_once 'controllers/cartController.php';

$cartController = new CartController();

$cartItems = $cartController->getCartItems($_SESSION['user']['id']);

$totalPrice = 0;
foreach ($cartItems as $cartItem) {
    $totalPrice += $cartItem['price'] * $cartItem['quantity'];
}

?>

<div id="orderSummary">
    <div class="text-2xl mx-4 mt-2 rounded px-2 bg-gray-200 font-semibold flex justify-between items-center">
        <div>Order Summary</div>
        <div>Total: ₹<span id="totalPrice">
                <?php echo $totalPrice; ?>
            </span></div>
    </div>
    <div class="p-4 rounded">
        <table class="w-full border">
            <thead>
                <tr class="bg-green-100">
                    <th class="py-2 px-4 border-r">Product Name</th>
                    <th class="py-2 px-4 border-r">Quantity</th>
                    <th class="py-2 px-4">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $cartItem) { ?>
                    <tr class="border">
                        <td class="py-2 px-4 border-r">
                            <?php echo $cartItem['title']; ?>
                        </td>
                        <td class="py-2 px-4 border-r">
                            <?php echo $cartItem['quantity']; ?>
                        </td>
                        <?php $totalPrice = $cartItem['price'] * $cartItem['quantity']; ?>
                        <td class="py-2 px-4">₹
                            <?php echo $totalPrice; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form method="POST" action="order.php">
            <div id="paymentMethodSelector">
                <div class="flex justify-between items-center mt-4 border p-2">
                    <label for="paymentMethod">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod"
                        class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-800">
                        <option value="cod">Cash on Delivery</option>
                        <option value="online">Online Payment</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Place Order
                </button>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'include/footer.php';
?>