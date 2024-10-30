<?php
// Prosta implementacja wyszukiwania
if (isset($_GET['query'])) {
    $query = strtolower($_GET['query']);
 

    $filteredProducts = array_filter($products, function ($product) use ($query) {
        return strpos(strtolower($product["name"]), $query) !== false;
    });

    foreach ($filteredProducts as $product) {
        echo "<div class='product-card'><h3>{$product['name']}</h3><p>Cena: {$product['price']} PLN</p></div>";
    }
}
?>
