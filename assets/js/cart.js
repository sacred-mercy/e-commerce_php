let productCardTemplate = document.getElementById("productCard");
productCardTemplate.remove();

const productObject = [];

// fetch cart items from database using ajax
let cartItems;
let xhr = new XMLHttpRequest();
xhr.open("GET", "routes/cartRoutes.php?request=getCartItems", true);
xhr.send();
xhr.onload = () => {
    if (xhr.status === 200) {
        cartItems = JSON.parse(xhr.response);
        if (cartItems.statusCode == 200) {
            cartItems = cartItems.cartItems;
            loadCartItems(cartItems);
            if (cartItems.length === 0) {
                document.getElementById("emptyCart").classList.remove("hidden");
            } else {
                document.getElementById("checkoutBtn").classList.remove("hidden");
            }
        } else {
            document.getElementById("productsContainer").innerText =
                "Something went wrong";
        }
    }
};

function showDialog(button) {
    // find the nearest card to the button
    let card = button.closest(".card");
    let id = parseInt(card.id);
    for (let product of productObject) {
        if (product.id == id) {
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

function loadCartItems(cartItems) {
    for (let item of cartItems) {
        productObject.push(item);
        let productCard = productCardTemplate.cloneNode(true);
        productCard.id = item.id;
        productCard.querySelector("#productImage").src = item.thumbnail;
        productCard.querySelector("#productName").innerText = item.title;
        productCard.querySelector("#productQty").innerText = item.quantity;
        productCard.classList.remove("hidden");
        productsContainer.appendChild(productCard);

        // add price to total
        let price = parseInt(item.price);
        let qty = parseInt(item.quantity);
        let final = parseInt(document.getElementById("totalPrice").innerText);
        final += price * qty;
        document.getElementById("totalPrice").innerText = final;
    }
}

function changeQty(button, isIncrease) {
    let productCard = button.parentElement.parentElement.parentElement;
    let id = productCard.id;
    let qty = productCard.querySelector("#productQty");
    let qtyValue = parseInt(qty.innerText);
    if (isIncrease) {
        qtyValue++;
    } else {
        qtyValue--;
    }
    if (qtyValue === 0) {
        deleteProduct(button);
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/cart/changeCartQty", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(
        JSON.stringify({
            id: id,
            qty: qtyValue,
        })
    );
    xhr.onload = () => {
        if (xhr.status === 200) {
            if (xhr.response === "Out of stock") {
                alert("Maximum quantity reached");
                return;
            }
            qty.innerText = qtyValue;
        }
    };
}

function deleteProduct(button) {
    let productCard = button.parentElement.parentElement.parentElement;
    let id = productCard.id;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "routes/cartRoutes.php?request=deleteCartItem&id=" + id, true);
    xhr.send();
    xhr.onload = () => {
        if (xhr.status === 200) {

            productCard.remove();

            // update total price
            for (let product of productObject) {
                if (product.id == id) {
                    console.log(product);
                    let price = parseInt(product.price);
                    let qty = parseInt(product.quantity);
                    let final = parseInt(document.getElementById("totalPrice").innerText);
                    final -= price * qty;
                    document.getElementById("totalPrice").innerText = final;
                    break;
                }
            }

            // delete from productObject
            for (let i = 0; i < productObject.length; i++) {
                if (productObject[i].id == id) {
                    productObject.splice(i, 1);
                    break;
                }
            }

            if (productObject.length === 0) {
                document.getElementById("emptyCart").classList.remove("hidden");
                document.getElementById("checkoutBtn").classList.add("hidden");
            }
        }
    };
}
