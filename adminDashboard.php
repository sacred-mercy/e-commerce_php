<?php
require_once 'include\header.php';

require_once 'controllers\adminController.php';
$adminController = new AdminController();
$data = $adminController->getDashboardData();

$totalOrders = $data['totalOrders'];
$totalProducts = $data['totalProducts'];
$totalUsers = $data['totalUsers'];
$totalRevenue = $data['totalRevenue'];
$todayOrders = $data['todayOrders'];
$todayRevenue = $data['todayRevenue'];
?>

<!-- Main Content -->
<div class=" p-8 bg-gray-100">
    <h2 class="text-3xl ml-7 font-bold">LifeTime Statistics</h2>
    <div class="grid grid-cols-4 gap-6 p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Total Orders</h3>
            <p class="text-2xl font-semibold">
                <?php echo $totalOrders; ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Total Products</h3>
            <p class="text-2xl font-semibold">
                <?php echo $totalProducts; ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Total Users</h3>
            <p class="text-2xl font-semibold">
                <?php echo $totalUsers; ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Total Revenue</h3>
            <p class="text-2xl font-semibold">
                ₹
                <?php echo $totalRevenue; ?>
            </p>
        </div>
    </div>

    <h2 class="text-3xl ml-7 font-bold">Today's Statistics</h2>
    <div class="grid grid-cols-4 gap-6 p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Today's Orders</h3>
            <p class="text-2xl font-semibold">
                <?php echo $todayOrders; ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-2">Today's Revenue</h3>
            <p class="text-2xl font-semibold">
                ₹
                <?php echo $todayRevenue; ?>
            </p>
        </div>
    </div>

    <div class="p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-bold text-xl mb-4">Most Sold Products</h3>

            <div class="flex items-center mb-4">
                <label for="timePeriod" class="mr-2">Time Period:</label>
                <select id="timePeriod" class="rounded-lg border-gray-300 px-2 w-1/6 py-1">
                    <option value="lifetime">Lifetime</option>
                    <option value="year">This Year</option>
                    <option value="month">This Month</option>
                    <option value="week">This Week</option>
                    <option value="day">Today</option>
                </select>
            </div>
            <ul class="space-y-2" id="mostSoldProducts">
                <li class="hidden border-b flex justify-between items-center" id="productTemplate">
                    <span class="font-semibold">Product A</span>
                    <span class="text-gray-500">100 sold</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    const timePeriod = document.getElementById('timePeriod');
    const productTemplate = document.getElementById('productTemplate').cloneNode(true);
    document.getElementById('productTemplate').remove();
    let value = timePeriod.value;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const products = response.products;
            const mostSoldProducts = document.getElementById('mostSoldProducts');
            mostSoldProducts.innerHTML = '';
            for (let i = 0; i < products.length; i++) {
                const product = products[i];
                const newProduct = productTemplate.cloneNode(true);
                newProduct.children[0].innerText = product.title;
                newProduct.children[1].innerText = `${product.product_count} sold`;
                newProduct.classList.remove('hidden');
                mostSoldProducts.appendChild(newProduct);
            }
        }
    };
    xmlhttp.open("GET", `routes/adminRoutes.php?timePeriod=${value}&request=getMostSoldProducts`, true);
    xmlhttp.send();

    timePeriod.addEventListener('change', () => {
        value = timePeriod.value;
        xmlhttp.open("GET", `routes/adminRoutes.php?timePeriod=${value}&request=getMostSoldProducts`, true);
        xmlhttp.send();
    });

</script>


<?php
require_once 'include\footer.php';
?>