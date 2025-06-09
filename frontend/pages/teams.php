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

// Fetch barbers data (limit to 3)
$barbers = fetchFromAPI('barber.php');
$barbers = array_slice($barbers, 0, 3); // Limit to 3 barbers
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Teams - Nine'O</title>
    <link rel="stylesheet" href="../frontend/assets/css/teams.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div id="navbar-placeholder"></div>

    <section id="teams" class="teams-section">
      <div class="container teams-container">
        <!-- Konten Teams di sini -->
        <h2>Our Teams</h2>
        <p>Meet the professionals behind Nine'O Barbershop.</p>

        <!-- Dynamic Team Cards -->
        <div class="card-container">
          <?php if (!empty($barbers)): ?>
            <?php foreach ($barbers as $barber): ?>
              <!-- Card for <?php echo htmlspecialchars($barber['name']); ?> -->
              <div class="card">
                <img
                  src="<?php echo !empty($barber['picture']) ? htmlspecialchars($barber['picture']) : 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/whatsapp-image-2024-11-23-at-18-38-04_e5pmnk.webp'; ?>"
                  alt="<?php echo htmlspecialchars($barber['name']); ?>"
                  class="profile-picture"
                />
                <div class="card-content">
                  <h3><?php echo htmlspecialchars($barber['name']); ?></h3>
                </div>
                <div class="arrow-button">&rarr;</div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Fallback static content if API fails -->
            <div class="card">
              <img
                src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/whatsapp-image-2024-11-23-at-18-38-04_e5pmnk.webp"
                alt="Team Member 1"
                class="profile-picture"
              />
              <div class="card-content">
                <h3>Panji</h3>
              </div>
              <div class="arrow-button">&rarr;</div>
            </div>

            <div class="card">
              <img
                src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/whatsapp-image-2024-11-23-at-18-38-04_e5pmnk.webp"
                alt="Team Member 2"
                class="profile-picture"
              />
              <div class="card-content">
                <h3>Rizal</h3>
              </div>
              <div class="arrow-button">&rarr;</div>
            </div>

            <div class="card">
              <img
                src="https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/whatsapp-image-2024-11-23-at-18-38-04_e5pmnk.webp"
                alt="Team Member 3"
                class="profile-picture"
              />
              <div class="card-content">
                <h3>Dika</h3>
              </div>
              <div class="arrow-button">&rarr;</div>
            </div>
          <?php endif; ?>
        </div>
        <!-- End of Card Container -->
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