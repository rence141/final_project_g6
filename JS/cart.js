document.addEventListener("DOMContentLoaded", () => {
    const cartTableBody = document.querySelector("#cartTable tbody");
    const totalPriceElement = document.querySelector("#totalPrice");


    const products = [
        { name: "Yamaha R1", price: 20000, image: "/img/imgs/462575313_3002269156616354_671729235541136851_n (1).png" },
        { name: "Yamaha MT-09", price: 10000, image: "/img/imgs/462636555_581482457593560_1502577366251939497_n.png" },
        { name: "Ducati Panigale V4", price: 30000, image: "/img/V4.jpeg" },
        { name: "Ferrari 125 S Motorcycle", price: 50000, image: "/img/125.jpeg" },
        { name: "Yamaha FZ-07", price: 7000, image: "/img/07.jpeg" },
        { name: "Ducati Monster 1200", price: 15000, image: "/img/monster.jpeg" },
        { name: "Yamaha R6", price: 18000, image: "/img/R6.jpeg" },
        { name: "Ducati Scrambler", price: 12000, image: "/img/scrammbler.jpeg" },
        { name: "Ferrari Corsa Moto", price: 45000, image: "/img/Moto.jpeg" },
        { name: "Yamaha Tenere 700", price: 11000, image: "/img/tenere.jpeg" },
        { name: "Ducati V2", price: 22000, image: "/img/V2.jpeg" },
        { name: "Ferrari 488 Moto", price: 55000, image: "/img/imgs/462561539_876501701304928_6281359818426048351_n.png" }
    ];

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Render Cart
    const renderCart = () => {
        cartTableBody.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            const row = document.createElement("tr");
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            row.innerHTML = `
                <td><img src="${item.image}" alt="${item.name}" width="50"></td>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" min="1" value="${item.quantity}" class="quantity-input" data-index="${index}">
                </td>
                <td>$${itemTotal.toFixed(2)}</td>
                <td><button class="remove-btn" data-index="${index}">Remove</button></td>
            `;
            cartTableBody.appendChild(row);
        });

        totalPriceElement.textContent = `Total: $${total.toFixed(2)}`;
        localStorage.setItem("cart", JSON.stringify(cart)); // Save to localStorage
    };

    // Update quantity
    cartTableBody.addEventListener("input", (event) => {
        if (event.target.classList.contains("quantity-input")) {
            const index = event.target.dataset.index;
            const newQuantity = parseInt(event.target.value);
            if (newQuantity > 0) {
                cart[index].quantity = newQuantity;
                renderCart(); // Re-render cart
            }
        }
    });

    // Remove item
    cartTableBody.addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-btn")) {
            const index = event.target.dataset.index;
            cart.splice(index, 1); // Remove item from cart
            renderCart(); // Re-render cart
        }
    });

    // Add to cart function
    const addToCart = (product) => {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        const existingItemIndex = cart.findIndex(item => item.name === product.name);

        if (existingItemIndex !== -1) {
            cart[existingItemIndex].quantity += 1; // Increase quantity
        } else {
            product.quantity = 1;  // Default quantity to 1
            cart.push(product);
        }

        localStorage.setItem("cart", JSON.stringify(cart)); // Save cart to localStorage
        renderCart(); // Re-render cart
    };

    // Render the product list with "Add to Cart" buttons
    const productListContainer = document.querySelector("#productList");
    products.forEach((product, index) => {
        const productElement = document.createElement("div");
        productElement.classList.add("product-item");
        productElement.innerHTML = `
            <img src="${product.image}" alt="${product.name}" width="100">
            <h3>${product.name}</h3>
            <p>$${product.price}</p>
            <button class="cart-btn-add" data-index="${index}">Add to Cart</button>
        `;
        productListContainer.appendChild(productElement);
    });

    // Attach event listeners to "Add to Cart" buttons
    const cartButtons = document.querySelectorAll(".cart-btn-add");
    cartButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            addToCart(products[index]); // Add corresponding product
        });
    });

    renderCart(); // Initial render of cart
});

document.addEventListener("DOMContentLoaded", () => {
    const cartTableBody = document.querySelector("#cartTable tbody");
    const totalPriceElement = document.querySelector("#totalPrice");

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    console.log("Cart loaded:", cart);

    const renderCart = () => {
        cartTableBody.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            const row = document.createElement("tr");
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            row.innerHTML = `
                <td><img src="${item.image}" alt="${item.name}"></td>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" min="1" value="${item.quantity}" class="quantity-input" data-index="${index}">
                </td>
                <td>$${itemTotal.toFixed(2)}</td>
                <td><button class="remove-btn" data-index="${index}">Remove</button></td>
            `;
            cartTableBody.appendChild(row);
        });

        totalPriceElement.textContent = `Total: $${total.toFixed(2)}`;
        localStorage.setItem("cart", JSON.stringify(cart)); // Save to localStorage
    };

    renderCart(); // Initial render of cart
});
