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

// Fetch services data (limit to 6)
$services = fetchFromAPI('services.php');
$services = array_slice($services, 0, 6); // Limit to 6 services

// Static icons for services (you can customize these based on service names)
$serviceIcons = [
    'default' => 'https://cdn-icons-png.flaticon.com/512/32/32069.png',
    'haircut' => 'https://cdn-icons-png.flaticon.com/512/32/32069.png',
    'washing' => 'https://cdn-icons-png.flaticon.com/128/2779/2779653.png',
    'color' => 'https://cdn-icons-png.flaticon.com/128/12525/12525116.png',
    'trim' => 'https://cdn-icons-png.flaticon.com/512/42/42063.png',
    'shave' => 'https://cdn1.iconfinder.com/data/icons/beard-and-mustache/100/all7_2_08_06-512.png',
    'massage' => 'https://cdn1.iconfinder.com/data/icons/spa-wellness-2/64/head-massage-relax-holidays-health-512.png'
];

// Function to get appropriate icon based on service name
function getServiceIcon($serviceName, $serviceIcons) {
    $name = strtolower($serviceName);
    
    if (strpos($name, 'haircut') !== false || strpos($name, 'cut') !== false) {
        return $serviceIcons['haircut'];
    } elseif (strpos($name, 'wash') !== false) {
        return $serviceIcons['washing'];
    } elseif (strpos($name, 'color') !== false) {
        return $serviceIcons['color'];
    } elseif (strpos($name, 'trim') !== false) {
        return $serviceIcons['trim'];
    } elseif (strpos($name, 'shave') !== false) {
        return $serviceIcons['shave'];
    } elseif (strpos($name, 'massage') !== false) {
        return $serviceIcons['massage'];
    } else {
        return $serviceIcons['default'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Services - Nine'O</title>
    <link rel="stylesheet" href="../frontend/assets/css/services.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div id="navbar-placeholder"></div>

    <section class="services" id="services">
      <p class="services-subtitle">Our Services</p>
      <h2 class="services-title">Popular Hair Cutting<br />And salon</h2>

      <div class="services-menu">
        <?php if (!empty($services)): ?>
          <?php foreach ($services as $service): ?>
            <div class="service-item">
              <img
                src="<?php echo getServiceIcon($service['name'], $serviceIcons); ?>"
                alt="<?php echo htmlspecialchars($service['name']); ?>"
              />
              <p><?php echo htmlspecialchars($service['name']); ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Fallback static content if API fails -->
          <div class="service-item">
            <img
              src="https://cdn-icons-png.flaticon.com/512/32/32069.png"
              alt="Trend Haircut"
            />
            <p>Trend Haircut</p>
          </div>
          <div class="service-item">
            <img
              src="https://cdn-icons-png.flaticon.com/128/2779/2779653.png"
              alt="Hair Washing"
            />
            <p>Hair Washing</p>
          </div>
          <div class="service-item">
            <img
              src="https://cdn-icons-png.flaticon.com/128/12525/12525116.png"
              alt="Hair Color"
            />
            <p>Hair Color</p>
          </div>
          <div class="service-item">
            <img
              src="https://cdn-icons-png.flaticon.com/512/42/42063.png"
              alt="Facial Hair Trim"
            />
            <p>Facial hair trim</p>
          </div>
          <div class="service-item">
            <img
              src="https://cdn1.iconfinder.com/data/icons/beard-and-mustache/100/all7_2_08_06-512.png"
              alt="Lather Shave"
            />
            <p>Lather shave</p>
          </div>
          <div class="service-item">
            <img
              src="https://cdn1.iconfinder.com/data/icons/spa-wellness-2/64/head-massage-relax-holidays-health-512.png"
              alt="Head Massage"
            />
            <p>Head Massage</p>
          </div>
        <?php endif; ?>
      </div>

      <div class="service-detail">
        <img
          src="https://www.stephenjohnhair.com/wp-content/uploads/2023/02/19-2.jpg"
          alt="Facial Trim"
          class="service-img"
        />
        <div class="service-text">
          <h3>Best Facial Hair Trim At Home Treatment</h3>
          <p>
            Kami menyediakan layanan berkualitas tinggi untuk menangani
            permasalahan rambut mulai dari kerontokan hingga kerusakan rambut
            yang lebih besar. Kami memiliki tim ahli yang berpengalaman dan
            terlatih untuk menangani permasalahan rambut secara profesional.
            Kami menggunakan produk yang berkualitas tinggi dan aman untuk
            digunakan pada rambut dan kami bisa dipanggil ke rumah.
          </p>
          <ul>
            <li>✅ Easy to use salon special offer navigation</li>
            <li>✅ We care about our customers satisfaction</li>
          </ul>
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