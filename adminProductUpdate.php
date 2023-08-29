<?php
require_once 'include/header.php';

// check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 't') {
    header('Location: index.php');
}

require_once 'controllers\productsController.php';
$productController = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = $productController->updateProduct($_POST);
    if ($product['statusCode'] === '200') {
        header('Location: adminProductsView.php');
    } else {
        echo $product['error'];
    }
}


// get all products
$product = $productController->getProductById($_GET['id']);
if ($product['statusCode'] === '200'){
    $product = $product['product'][0];
} else {
    echo $product['error'];
    exit();
}
?>



<div class="bg-gray-100 p-4">
    <div class="  bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Update Product</h1>

        <form action="adminProductUpdate.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

            <div class="mb-4">
                <label class=" text-gray-700 font-bold mb-2" for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo $product['title']; ?>"
                    class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class=" text-gray-700 font-bold mb-2" for="description">Description:</label>
                <textarea name="description" id="description"
                    class="w-full px-3 py-2 border rounded-lg"><?php echo $product['description']; ?></textarea>
            </div>

            <div class="mb-4">
                <label class=" text-gray-700 font-bold mb-2" for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" value="<?php echo $product['price']; ?>"
                    class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class=" text-gray-700 font-bold mb-2" for="brand">Brand:</label>
                <input type="text" name="brand" id="brand" value="<?php echo $product['brand']; ?>"
                    class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class=" text-gray-700 font-bold mb-2" for="stock">Stock:</label>
                <input type="number" name="stock" id="stock" value="<?php echo $product['stock']; ?>"
                    class="w-full px-3 py-2 border rounded-lg">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update</button>
        </form>
    </div>
</div>