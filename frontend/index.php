<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nine'O Barbershop</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css" />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navbar Placeholder -->
    <div id="navbar-placeholder"></div>

    <!-- Sections -->
    <div id="section-home"></div>
    <div id="section-aboutus"></div>
    <div id="section-products"></div>
    <div id="section-teams"></div>
    <div id="section-services"></div>
    <div id="section-contact"></div>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <p>&copy; 2025 Nine'O Barbershop. All rights reserved.</p>
      </div>
    </footer>

    <!-- Script to Load External HTML Files -->
    <script>
      const sections = {
  "section-home": "pages/home.php",
  "section-aboutus": "pages/aboutus.php",
  "section-teams": "pages/teams.php",
  "section-services": "pages/services.php",
  "section-products": "pages/products.php",
  "section-contact": "pages/contact.php",
};


      Object.entries(sections).forEach(([id, url]) => {
        fetch(url)
          .then((res) => res.text())
          .then((data) => {
            document.getElementById(id).innerHTML = data;
          })
          .catch((err) => console.error(`Gagal memuat ${url}: `, err));
      });

      // Load Navbar
      fetch("pages/navbar.php")
      .then((res) => res.text())
        .then((data) => {
          document.getElementById("navbar-placeholder").innerHTML = data;
        })
        .catch((err) => console.error("Gagal memuat navbar.php: ", err));
    </script>
  </body>
</html>