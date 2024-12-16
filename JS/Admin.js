// Sample products (replace this with data from an actual backend/database if applicable)
let products = [
    { id: 1, name: "Product A", price: 100, stock: 20 },
    { id: 2, name: "Product B", price: 150, stock: 15 }
];

// Open Product Management Section
function openProductManagement() {
    document.getElementById("product-management").style.display = "block";
    renderProductList();
}

// Render Product List
function renderProductList() {
    const productList = document.getElementById("product-list");
    productList.innerHTML = "";

    products.forEach(product => {
        const listItem = document.createElement("li");
        listItem.innerHTML = `
            <strong>${product.name}</strong> - $${product.price}, Stock: ${product.stock}
            <button onclick="editProduct(${product.id})">Edit</button>
            <button onclick="removeProduct(${product.id})">Remove</button>
        `;
        productList.appendChild(listItem);
    });
}

// Add or Edit Product
function addOrEditProduct() {
    const productId = document.getElementById("product-id").value;
    const productName = document.getElementById("product-name").value;
    const productPrice = document.getElementById("product-price").value;
    const productStock = document.getElementById("product-stock").value;

    if (!productName || !productPrice || !productStock) {
        alert("Please fill out all fields");
        return;
    }

    if (productId) {
        // Edit product
        const product = products.find(p => p.id == productId);
        product.name = productName;
        product.price = parseFloat(productPrice);
        product.stock = parseInt(productStock);
    } else {
        // Add new product
        const newProduct = {
            id: Date.now(),
            name: productName,
            price: parseFloat(productPrice),
            stock: parseInt(productStock)
        };
        products.push(newProduct);
    }

    // Reset form
    document.getElementById("product-form").reset();
    document.getElementById("product-id").value = "";

    // Update product list
    renderProductList();
}

// Edit Product
function editProduct(id) {
    const product = products.find(p => p.id == id);

    document.getElementById("product-id").value = product.id;
    document.getElementById("product-name").value = product.name;
    document.getElementById("product-price").value = product.price;
    document.getElementById("product-stock").value = product.stock;
}

// Remove Product
function removeProduct(id) {
    products = products.filter(p => p.id !== id);
    renderProductList();
}
