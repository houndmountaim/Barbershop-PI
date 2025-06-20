<?php
// Function to fetch data from API
function fetchFromAPI($endpoint) {
    $url = "http://localhost:8080/Barbershop-PI/backend/api/" . $endpoint;
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return [];
    }

    $data = json_decode($response, true);
    return $data && isset($data['data']) ? $data['data'] : [];
}

// Fetch products data (limit to 4)
$products = fetchFromAPI('products.php');
$products = array_slice($products, 0, 4);

// Format price
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

// Get product image
function getProductImage($product) {
    if (!empty($product['picture'])) {
        return $product['picture'];
    }
    if (!empty($product['image'])) {
        return $product['image'];
    }

    $name = strtolower($product['name']);
    if (strpos($name, 'facial') !== false || strpos($name, 'wash') !== false) {
        return 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/7516331b72794c35a5c96c33a5098a53_.jpeg_f10xpr.jpg';
    } elseif (strpos($name, 'pomade') !== false) {
        return 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746945/0dbaea48-f64f-4701-8900-65b671f1894d.jpg_yzpbny.jpg';
    } elseif (strpos($name, 'tonic') !== false) {
        return 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746947/b1e4cd45-72d6-45c8-8ad5-23393344ede1.jpg_hmh4tu.jpg';
    } elseif (strpos($name, 'shampoo') !== false) {
        return 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/a571642bf4c545889505e76b035c423c_.jpeg_yja9p4.jpg';
    } else {
        return 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/7516331b72794c35a5c96c33a5098a53_.jpeg_f10xpr.jpg';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Products - Nine'O</title>
    <link rel="stylesheet" href="../frontend/assets/css/products.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <div id="navbar-placeholder"></div>

    <section id="products" class="products-section">
        <div class="container">
            <h2>Our Products</h2>
            <p class="subtitle">Evoke the experience of one of our barber shops</p>
            <div class="product-grid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-item">
                            <img src="<?php echo getProductImage($product); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                            <p><?php echo htmlspecialchars($product['name']); ?></p>
                            <span><?php echo formatPrice($product['price']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="product-item">
                        <img src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/7516331b72794c35a5c96c33a5098a53_.jpeg_f10xpr.jpg" alt="Product 1" />
                        <p>New Facial Wash</p>
                        <span>Rp 50.000</span>
                    </div>
                    <div class="product-item">
                        <img src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746945/0dbaea48-f64f-4701-8900-65b671f1894d.jpg_yzpbny.jpg" alt="Product 2" />
                        <p>New Pomade</p>
                        <span>Rp 60.000</span>
                    </div>
                    <div class="product-item">
                        <img src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746947/b1e4cd45-72d6-45c8-8ad5-23393344ede1.jpg_hmh4tu.jpg" alt="Product 3" />
                        <p>New Hair Tonic</p>
                        <span>Rp 40.000</span>
                    </div>
                    <div class="product-item">
                        <img src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/a571642bf4c545889505e76b035c423c_.jpeg_yja9p4.jpg" alt="Product 4" />
                        <p>New Fresh Shampoo</p>
                        <span>Rp 70.000</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        fetch("/frontend/navbar.php")
            .then((res) => res.text())
            .then((data) => {
                document.getElementById("navbar-placeholder").innerHTML = data;
            });
    </script>
</body>
</html>
