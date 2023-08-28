<?php
require_once 'include/header.php';

// Get the current page number from the query parameter
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Set the number of products per page
$products_per_page = 2;

// get all products
require_once 'controllers\productsController.php';
$productController = new ProductController();
$products = $productController->getAllProducts($current_page, $products_per_page);

// Get the total number of products
$total_products = $productController->getTotalProducts();

// Calculate the total number of pages
$total_pages = ceil($total_products['count'] / $products_per_page);
?>

<div class="bg-gray-100 p-2">

    <div class="mx-auto">
        <div class="flex justify-between">
        <h1 class="text-2xl flex justify-center font-bold mb-4">Product Details</h1>
        <a href="adminProductCreate.php" class="flex justify-center text-white p-2 rounded bg-blue-500 font-bold mb-4">Create Product</a>
        </div>
        <table class="w-full border">
            <thead class="bg-gray-800 text-gray-100">
                <tr>
                    <th class="border p-2">Thumbnail</th>
                    <th class="border p-2">Title</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Price</th>
                    <th class="border p-2">Brand</th>
                    <th class="border p-2">Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products['products'] as $product) { ?>
                    <tr class="bg-white hover:bg-gray-200 cursor-pointer" title="Click to edit"
                        onclick="window.location.href='adminProductUpdate.php?id=<?php echo $product['id']; ?>'">
                        <td class="border p-2"><img src="<?php echo IMAGE_PATH . $product['thumbnail']; ?>"
                                alt="<?php echo $product['title']; ?>" class="w-20 h-10"></td>
                        <td class="border p-2">
                            <?php echo $product['title']; ?>
                        </td>
                        <td class="border p-2">
                            <?php echo $product['description']; ?>
                        </td>
                        <td class="border p-2">
                            <?php echo $product['price']; ?>
                        </td>
                        <td class="border p-2">
                            <?php echo $product['brand']; ?>
                        </td>
                        <td class="border p-2">
                            <?php echo $product['stock']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="flex justify-center mt-4">
            <ul class="flex pl-0 list-none rounded my-2">
                <?php if ($current_page > 1) { ?>
                    <li class="mr-1">
                        <a href="?page=<?php echo $current_page - 1; ?>" class="block py-2 px-3 text-gray-600 rounded hover:bg-gray-200">&lt;</a>
                    </li>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="mr-1">
                        <a href="?page=<?php echo $i; ?>" class="block py-2 px-3 text-gray-600 rounded hover:bg-gray-200 <?php if ($current_page == $i) { echo 'bg-gray-200'; } ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>

                <?php if ($current_page < $total_pages) { ?>
                    <li class="mr-1">
                        <a href="?page=<?php echo $current_page + 1; ?>" class="block py-2 px-3 text-gray-600 rounded hover:bg-gray-200">&gt;</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

</div>


<?php
require_once 'include/footer.php';
?>