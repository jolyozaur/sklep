document.addEventListener('DOMContentLoaded', function () {
  loadProducts()
})

function loadProducts() {
  fetchProducts('')
}

function fetchProducts(query) {
  fetch(`products.php?query=${query}`)
    .then((response) => response.json())
    .then((data) => {
      displayProducts(data)
    })
    .catch((error) => console.error('Error fetching products:', error))
}
function toggleCart() {
  const cart = document.getElementById('cart-content');
  cart.style.display = cart.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function (event) {
  if (
    !event.target.closest('.cart-icon') &&
    !event.target.closest('.cart-content')
  ) {
    const cartContent = document.getElementById('cart-content')
    if (cartContent.style.display === 'block') {
      cartContent.style.display = 'none'
    }
  }
}

function displayProducts(products) {
  const productContainer = document.getElementById('products')
  productContainer.innerHTML = '' 

  products.forEach((product) => {
    const productCard = document.createElement('div')
    productCard.className = 'product-card'
    productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>Cena: ${product.price} PLN</p>
            <button onclick="location.href='product-details.php?id=${product.id}'">Zobacz szczegóły</button>
        `
    productContainer.appendChild(productCard)
  })
}

function searchProduct() {
  const query = document.getElementById('search-input').value.toLowerCase()
  fetchProducts(query)
}
function displayProducts(products) {
  const productContainer = document.getElementById('products')
  productContainer.innerHTML = '' 

  products.forEach((product) => {
    const productCard = document.createElement('div')
    productCard.className = 'product-card'
    productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>Cena: ${product.price} PLN</p>
            <form method="POST" action="index.php">
                <input type="hidden" name="product_id" value="${product.id}">
                <input type="hidden" name="product_name" value="${product.name}">
                <input type="hidden" name="product_price" value="${product.price}">
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Dodaj do koszyka</button>
            </form>
            <button onclick="location.href='product-details.php?id=${product.id}'">Zobacz szczegóły</button>
        `
    productContainer.appendChild(productCard)
  })
}

function filterProducts(type) {
  fetch(`get_products.php?type=${type}`)
    .then((response) => response.json())
    .then((data) => {
      displayProducts(data)
    })
    .catch((error) =>
      console.error('Błąd podczas pobierania produktów:', error),
    )
}
var modal = document.getElementById('myModal')

var images = document.querySelectorAll('.gallery-images img')

images.forEach(function (image) {
  image.onclick = function () {
    modal.style.display = 'block'
    var modalImg = document.getElementById('img01')
    var captionText = document.getElementById('caption')
    modalImg.src = this.src 
    captionText.innerHTML = this.alt 
  }
})

var span = document.getElementsByClassName('close')[0]

span.onclick = function () {
  modal.style.display = 'none'
}

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = 'none'
  }
}
