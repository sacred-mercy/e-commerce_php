<?php
require_once 'include/header.php';

// check if session is set
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

require_once 'controllers/cartController.php';
require_once 'controllers/addressController.php';

$cartController = new CartController();
$addressController = new AddressController();

// check for post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check if address is set
    if (isset($_POST['line1']) && isset($_POST['line2']) && isset($_POST['district']) && isset($_POST['state']) && isset($_POST['pincode'])) {
        // add address
        $addressObj = [
            'line1' => $_POST['line1'],
            'line2' => $_POST['line2'],
            'district' => $_POST['district'],
            'state' => $_POST['state'],
            'pincode' => $_POST['pincode']
        ];

        $addressController->addAddress($_SESSION['user']['id'], $addressObj);
    }
}

$cartItems = $cartController->getCartItems($_SESSION['user']['id']);
$address = $addressController->getAddress($_SESSION['user']['id']);

$totalPrice = 0;
if ($cartItems['statusCode'] === '200') {
    // if cart is empty
    if (count($cartItems['cartItems']) == 0) {
        header('location: cart.php');
        exit();
    }
    $cartItems = $cartItems['cartItems'];
    foreach ($cartItems as $cartItem) {
        $totalPrice += $cartItem['price'] * $cartItem['quantity'];
    }
} else {
    $cartItems = array();
}

// create order session for total price
$_SESSION['order'] = array(
    'totalPrice' => $totalPrice
);

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
                        <?php $price = $cartItem['price'] * $cartItem['quantity']; ?>
                        <td class="py-2 px-4">₹
                            <?php echo $price; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form method="POST" action="pay.php">
            <div class="my-3 border rounded shadow-sm p-2">
                <div class="flex justify-between items-center">
                    <h4 class="text-xl my-2 font-semibold">Select Address</h4>
                    <button type="button" id="addAddress"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        onclick="showAddressForm()">
                        Add Address
                    </button>
                </div>
                <?php foreach ($address['address'] as $address) { ?>
                    <div class=" flex justify-between items-center border-b">
                        <label class="flex items-center mt-2 space-x-2">
                            <input type="radio" name="address" class="form-radio" value="<?php echo $address['id']; ?>"
                                required>
                            <div class="mx-4">
                                <p>
                                    <?php echo $address['line1'], ', ', $address['line2']; ?>
                                </p>
                                <p>
                                    <?php echo $address['district'], ', ', $address['state'], ', ', $address['pincode']; ?>
                            </div>
                        </label>
                        <button type="button"
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 m-0"
                            onclick="deleteAddress(<?php echo $address['id']; ?>)">
                            Delete
                        </button>
                    </div>
                <?php } ?>

            </div>

            <div id="paymentMethodSelector">
                <div class="flex justify-between items-center mt-4 border p-2">
                    <label for="paymentMethod">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod"
                        class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-800">
                        <option value="online">Online Payment</option>
                        <option value="cod">Cash on Delivery</option>
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

<dialog
    class="bg-white dark:bg-gray-800 rounded-md w-11/12 md:w-1/2 lg:w-1/3 mx-auto my-4 overflow-auto z-50 border border-gray-300">
    <div class="flex justify-between items-center p-4 border-b border-gray-300">
        <h2 class="text-xl font-semibold">Add Address</h2>
        <button class="text-gray-600 hover:text-gray-700" onclick="closeAddressForm()">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path
                    d="M18.707 5.293a1 1 0 0 1 0 1.414L7.414 18H10a1 1 0 0 1 0 2H6a1 1 0 0 1-1-1v-4a1 1 0 0 1 2 0v2.586l11.293-11.293a1 1 0 0 1 1.414 0z">
                </path>
            </svg>
        </button>
    </div>
    <form action="orderCheckout.php" method="POST">
        <div class="p-4 space-y-4">
            <div>
                <label for="line1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                <div class="mt-1">
                    <input type="text" name="line1" id="line1"
                        class="form-input block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                        placeholder="Address Line 1">
                </div>
            </div>
            <div>
                <label for="line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                <div class="mt-1">
                    <input type="text" name="line2" id="line2"
                        class="form-input block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                        placeholder="Address Line 2">
                </div>
            </div>
            <div>
                <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                <div class="mt-1">
                    <input type="text" name="district" id="district"
                        class="form-input block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                        placeholder="District">
                </div>
            </div>
            <div>
                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                <div class="mt-1">
                    <input type="text" name="state" id="state"
                        class="form-input block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                        placeholder="State">
                </div>
            </div>
            <div>
                <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                <div class="mt-1">
                    <input type="text" name="pincode" id="pincode"
                        class="form-input block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md"
                        placeholder="Pincode">
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add Address
                </button>
            </div>
        </div>
    </form>
</dialog>

<script>
    function showAddressForm() {
        document.getElementById('addAddress').style.display = 'none';
        document.getElementById('paymentMethodSelector').style.display = 'none';
        document.getElementById('orderSummary').style.display = 'none';
        document.querySelector('dialog').showModal();
    }

    function closeAddressForm() {
        document.getElementById('addAddress').style.display = 'block';
        document.getElementById('paymentMethodSelector').style.display = 'block';
        document.getElementById('orderSummary').style.display = 'block';
        document.querySelector('dialog').close();
    }
</script>

<?php
require_once 'include/footer.php';
?>