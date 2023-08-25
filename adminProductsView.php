<?php
require_once 'include/header.php';

// get all products
require_once 'controllers\productsController.php';
$productController = new ProductController();
$products = $productController->getAllProducts();
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
    </div>

</div>


<?php
require_once 'include/footer.php';
?>