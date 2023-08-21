<?php require_once 'views/partials/header.php'; ?>

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
                <div class="flex justify-between mt-1">
                    <!-- Add to cart btn -->
                    <button
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Add to Cart
                    </button>
                    <button onclick="showDialog(this)" href="#"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex justify-center">
    <button id="LoadMoreBtn"
        class="hidden text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
        onclick="loadMore()">
        Load More
    </button>
</div>

<dialog id="dialog" class="rounded bg-white border dark:bg-gray-900 dark:text-white">
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
                        <button
                            class="flex ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</dialog>
<script src="./public/js/index.js"></script>

<?php require_once 'views/partials/footer.php'; ?>