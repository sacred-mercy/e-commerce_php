<?php

require_once 'include/header.php';
require_once 'controllers/ProductsController.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 't') {
    header('Location: index.php');
}

$dataObject = [
    'title' => '',
    'description' => '',
    'price' => '',
    'brand' => '',
    'stock' => '',
    'image' => '',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dataObject = $_POST;
    $dataObject['image'] = $_FILES['image'];
    print_r($dataObject);


    $productController = new ProductController();

    $result = $productController->createProduct($dataObject);

    if ($result['statusCode'] == '200') {
        header('Location: adminProductsView.php');
    } else {
        echo '<div class="bg-red-500 text-white px-4 py-2 rounded-lg">Something went wrong!</div>';
    }


    // $query = "INSERT INTO products (title, description, price, brand, stock, image) 
    //             VALUES ('$title', '$description', $price, '$brand', $stock, '$imagePath')";

    // $result = pg_query($db, $query);
}

?>

<div class="bg-gray-100 p-8">

    <div class="max-w-md mx-auto bg-white p-6 shadow-md">
        <h1 class="text-2xl flex justify-center font-bold mb-4">Create New Product</h1>

        <form action="adminProductCreate.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="title">Title:</label>
                <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded-lg"
                    value="<?php echo $dataObject['title']; ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="description">Description:</label>
                <textarea name="description" id="description"
                    class="w-full px-3 py-2 border rounded-lg"><?php echo $dataObject['description']; ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" class="w-full px-3 py-2 border rounded-lg"
                    value="<?php echo $dataObject['price']; ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="brand">Brand:</label>
                <input type="text" name="brand" id="brand" class="w-full px-3 py-2 border rounded-lg"
                    value="<?php echo $dataObject['brand']; ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="stock">Stock:</label>
                <input type="number" name="stock" id="stock" class="w-full px-3 py-2 border rounded-lg"
                    value="<?php echo $dataObject['stock']; ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="image">Image:</label>
                <input type="file" name="image" id="image" class="w-full px-3 py-2 border rounded-lg">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Create
                Product</button>
        </form>
    </div>
</div>

<?php
require_once 'include/footer.php';
?>