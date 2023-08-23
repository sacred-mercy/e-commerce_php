let productCardTemplate = document.getElementById("productCard");
productCardTemplate.remove();

let currentIndex = 0;

// fetch products.json from database folder using ajax
let productsData;
let xhr = new XMLHttpRequest();
xhr.open("GET", "routes/productRoute.php");
xhr.send();
xhr.onload = () => {
    if (xhr.status === 200) {
        productsData = JSON.parse(xhr.response);
        if(productsData.statusCode == 200){
            productsData = productsData.products;
            loadProducts(productsData.slice(0, 5));
            if (productsData.length <= 5) {
                document.getElementById("loadMore").classList.add("hidden");
            }
            if (productsData.length === 0) {
                document.getElementById("productsContainer").innerText =
                "No products found";
            } else {
                document.getElementById("LoadMoreBtn").classList.remove("hidden");
            }
        } else {
            document.getElementById("productsContainer").innerText =
            "Something went wrong";
        }
    }
};

function loadProducts(products) {
    let productsContainer = document.getElementById("productsContainer");
    currentIndex += products.length;
    for (let product of products) {
        let productCard = productCardTemplate.cloneNode(true);
        productCard.id = product.id;
        productCard.querySelector("#productImage").src = product.thumbnail;
        productCard.querySelector("#productName").innerText = product.title;
        productCard.classList.remove("hidden");
        productsContainer.appendChild(productCard);
    }
}

function loadMore() {
    console.log(currentIndex);
    let products = productsData.slice(currentIndex, currentIndex + 5);
    loadProducts(products);
    if (currentIndex >= productsData.length) {
        document.getElementById("LoadMoreBtn").classList.add("hidden");
    }
}

function showDialog(button) {
    // find the nearest card to the button
    let card = button.closest(".card");
    let id = card.id;
    showModal(parseInt(id));
}

function showModal(id) {
    for (let product of productsData) {
        if (product.id == id) {
            console.log(product);
            let dialog = document.getElementById("dialog");
            dialog.querySelector("#productImageDialog").src = product.thumbnail;
            dialog.querySelector("#productNameDialog").innerText =
                product.title;
            dialog.querySelector("#productBrandDialog").innerText =
                product.brand;
            dialog.querySelector("#productPriceDialog").innerText =
                product.price;
            dialog.querySelector("#productDescriptionDialog").innerText =
                product.description;
            window.dialog.showModal();
            break;
        }
    }
}

function addToCart(button) {
    let card = button.closest(".card");
    let id = card.id;
    // ajax request to add product to cart
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "routes/cartRoutes.php?request=addProduct&productId=" + id, true);
    xhr.send();
    xhr.onload = () => {
        if (xhr.status === 200) {
            if (xhr.response === "notLoggedIn") {
                alert("Please login to add products to cart");
                return;
            }
            alert("Product added to cart successfully");
        }
    }
}