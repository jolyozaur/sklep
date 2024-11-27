document.addEventListener('DOMContentLoaded', function () {
  loadProducts()
})

function loadProducts() {
  // Ładowanie wszystkich produktów bez filtracji
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
  productContainer.innerHTML = '' // Wyczyść poprzednie produkty

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
  productContainer.innerHTML = '' // Wyczyść poprzednie produkty

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

// Funkcja do pobierania produktów na podstawie typu
function filterProducts(type) {
  // Przekazanie typu do nowego endpointu PHP
  fetch(`get_products.php?type=${type}`)
    .then((response) => response.json())
    .then((data) => {
      displayProducts(data)
    })
    .catch((error) =>
      console.error('Błąd podczas pobierania produktów:', error),
    )
}

// Funkcja do wyświetlania produktów na stronie

// Pobierz modal
var modal = document.getElementById('myModal')

// Pobierz wszystkie obrazy w galerii
var images = document.querySelectorAll('.gallery-images img')

// Dla każdego obrazu dodaj zdarzenie kliknięcia
images.forEach(function (image) {
  image.onclick = function () {
    modal.style.display = 'block'
    var modalImg = document.getElementById('img01')
    var captionText = document.getElementById('caption')
    modalImg.src = this.src // Ustaw źródło obrazu modala
    captionText.innerHTML = this.alt // Ustaw podpis
  }
})

// Pobierz element zamykający modal
var span = document.getElementsByClassName('close')[0]

// Kiedy użytkownik kliknie na zamknij, zamknij modal
span.onclick = function () {
  modal.style.display = 'none'
}

// Zamknij modal, gdy użytkownik kliknie w tło
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = 'none'
  }
}
