CREATE DATABASE barbershop;
USE barbershop;

CREATE TABLE barber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,

    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE barber_service (
    barber_id INT NOT NULL,
    service_id INT NOT NULL,
    PRIMARY KEY (barber_id, service_id),
    FOREIGN KEY (barber_id) REFERENCES barber(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

INSERT INTO barber (name, description, phone, email, picture) VALUES
('Ujang', 'Kapster ramah dengan gaya potongan modern dan cepat.', '08123456789', 'ujang@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/whatsapp-image-2024-11-23-at-18-38-04_e5pmnk.webp'),
('Jarwo', 'Pakar warna rambut dengan pengalaman di salon internasional.', '08234567890', 'jarwo@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746108/fotoprofile-linktree-panji_gkasib.webp'),
('Dadang', 'Spesialis cukur jenggot dan styling brewok rapi.', '08345678901', 'dadang@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/fotoprofile-linktree-opik_e0mfoc.webp'),
('Cecep', 'Menguasai berbagai teknik potong untuk anak muda.', '08456789012', 'cecep@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746109/fotoprofile-linktree-vian_pjetvx.webp'),
('Asep', 'Berfokus pada perawatan kulit kepala dan kesehatan rambut.', '08567890123', 'asep@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746108/fotoprofile-linktree-putu_wycm9t.webp'),
('Budi', 'Menyediakan layanan pijat kepala dan relaksasi.', '08678901234', 'budi@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746108/fotoprofile-linktree-dio_buwbmn.webp'),
('Jajang', 'Ahli dalam memberikan rekomendasi produk perawatan rambut.', '08789012345', 'jajang@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746108/fotoprofile-linktree-arsat_eq1har.webp'),
('Rudi', 'Kapster senior dengan pengalaman lebih dari 10 tahun.', '08890123456', 'rudi@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746108/fotoprofile-linktree-bayu_xdbff0.webp'),
('Eko', 'Suka mengikuti perkembangan trend gaya rambut dunia.', '08901234567', 'eko@example.com', 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746107/8ef793ad-ecb7-4e4a-a9cc-c8dfdd9e9841_umusee.webp');

INSERT INTO services (name, description, price, duration_minutes) VALUES
('Modern Trend Haircut', 'Layanan potong rambut dengan gaya terkini dan paling populer di kalangan anak muda urban.', 55000, 30),
('Deep Cleanse Hair Wash', 'Proses pencucian rambut menggunakan shampoo premium yang membersihkan secara mendalam dan menyehatkan kulit kepala.', 25000, 20),
('Professional Hair Coloring', 'Pewarnaan rambut dilakukan oleh ahli dengan formula aman dan hasil tahan lama.', 160000, 70),
('Precision Facial Hair Trim', 'Pemangkasan jenggot dan brewok secara presisi sesuai bentuk wajah pelanggan.', 30000, 15),
('Classic Lather Shave', 'Cukur halus menggunakan sabun panas tradisional untuk pengalaman berkualitas.', 35000, 20),
('Relaxing Head Massage', 'Pijat kepala dan leher untuk melepas ketegangan otot serta meningkatkan sirkulasi darah.', 40000, 25),
('Beard Styling Treatment', 'Perawatan jenggot agar tetap lembut, berkilau, dan mudah ditata.', 45000, 30),
('Full Grooming Package', 'Paket lengkap mencakup haircut, wash, massage, dan styling untuk penampilan maksimal.', 180000, 90);

INSERT INTO products (name, description, price, stock, picture) VALUES
('Shampoo Anti Dandruff', 'Untuk mengurangi ketombe secara efektif.', 50000, 60, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/a571642bf4c545889505e76b035c423c_.jpeg_yja9p4.jpg'),
('Pomade Matte', 'Styling cream untuk tampilan rapi dan matte finish.', 75000, 30, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746945/0dbaea48-f64f-4701-8900-65b671f1894d.jpg_yzpbny.jpg'),
('Hair Tonic', 'Menyehatkan akar rambut dan mencegah kerontokan.', 60000, 40, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746947/b1e4cd45-72d6-45c8-8ad5-23393344ede1.jpg_hmh4tu.jpg'),
('Facial Wash', 'Pembersih wajah khusus pria.', 45000, 50, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/7516331b72794c35a5c96c33a5098a53_.jpeg_f10xpr.jpg'),
('Beard Oil', 'Minyak penumbuh dan pelembut jenggot.', 80000, 25, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746945/1b959c5b-6e77-4b23-9144-a650cfdff4c5.jpg_ysngmf.jpg'),
('Hair Cream', 'Cream styling harian untuk semua jenis rambut.', 55000, 35, 'https://res.cloudinary.com/du33tbey1/image/upload/v1747746946/334888e5-853c-4a7e-a7df-160eececeb0b.jpg_ophviy.webp');