<?php
require_once 'include/header.php';

// check if session is set
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

?>

<div class="text-2xl mx-4 mt-2 rounded px-2 bg-gray-200 font-semibold flex justify-between items-center">
    <p>Cart</p>
    <p>Total: ₹<span id="totalPrice">0</span></p>
    <a id="checkoutBtn" href="checkout.php"
        class=" text-white p-2 m-1 btn-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none
        focus:ring-blue-300 font-medium rounded-lg text-sm text-center dark:bg-blue-600 dark:hover:bg-blue-700
        dark:focus:ring-blue-800">
        Checkout
    </a>
</div>
<div id="emptyCart" class="hidden">
    <div class="flex justify-center items-center h-96">
        <h1 class="text-2xl font-semibold">Your cart is empty</h1>
        <a href="index.php"
            class="text-white bg-blue-700 hover:bg-blue-800 mx-3 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Go
            to shop</a>
    </div>
</div>
<div class="flex justify-center">
    <div id="productsContainer"
        class="p-3 grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        <div id="productCard"
            class="card hidden w-full max-w-sm m-1 bg-white border border-gray-200 rounded-lg shadow-inner dark:bg-gray-800 dark:border-gray-700">
            <img id="productImage" class="p-8 rounded h-48 w-full object-cover object-center" src="" alt="product image"
                loading="lazy" />

            <div class="px-5 pb-5">
                <div class="flex justify-center">
                    <h5 id="productName"
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white h-16 overflow-ellipsis overflow-hidden">
                    </h5>
                </div>
                <div class="flex justify-evenly mt-1">
                    <button onclick="deleteProduct(this)"
                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Delete
                    </button>
                    <button onclick="showDialog(this)"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        View Details
                    </button>
                </div>
                <div class="flex justify-evenly items-center">
                    <button onclick="changeQty(this, false)"
                        class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        -
                    </button>
                    <div id="productQty"
                        class="flex items-center text-xl font-semibold tracking-tight text-gray-900 dark:text-white h-16 overflow-ellipsis overflow-hidden">
                    </div>
                    <button onclick="changeQty(this, true)"
                        class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        +
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="dialog" class="rounded bg-white border dark:bg-gray-900 dark:text-white h-5/6 w-3/4 fixed top-0 left-0">
    <button onclick="window.dialog.close();" aria-label="close" class="float-right">
        ❌
    </button>
    <section class="text-gray-700 body-font overflow-hidden bg-white dark:bg-gray-900 dark:text-white">
        <div class="container px-5 py-24 mx-auto">
            <div class="mx-auto flex">
                <img id="productImageDialog" alt="E-commerce"
                    class="lg:w-1/3 w-full object-cover object-center rounded border border-gray-200" src="" />
                <div class="lg:w-8/12 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h2 id="productBrandDialog" class="text-sm title-font text-gray-500 tracking-widest"></h2>
                    <h1 id="productNameDialog" class="text-3xl title-font font-medium mb-1"></h1>

                    <p class="leading-relaxed" id="productDescriptionDialog"></p>

                    <div class="flex border-t-2 pt-2 mt-1">
                        <span class="title-font font-medium text-2xl">
                            ₹
                        </span>
                        <span id="productPriceDialog" class="title-font font-medium text-2xl"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</dialog>

<script src="assets/js/cart.js"></script>

<?php
require_once 'include/footer.php';
?>