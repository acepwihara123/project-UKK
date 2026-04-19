<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $camera['nama']; ?> - Detail Kamera | Rental Kamera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .hero-bg {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.9), rgba(185, 28, 28, 0.9));
        }
        .spec-card {
            border-left: 4px solid #dc2626;
        }
        .price-badge {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
            display: inline-block;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- PHP Connection and Data Fetching -->
    <?php
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rental_kamera";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get camera ID from URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id == 0) {
        header("Location: index.html");
        exit();
    }
    
    // Fetch camera data
    $sql = "SELECT * FROM kamera WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $camera = $result->fetch_assoc();
        
        // Format price
        $formatted_price = "Rp " . number_format($camera['harga_sewa'], 0, ',', '.');
        
        // Stock status
        $badge_color = $camera['stok'] > 0 ? "bg-green-500" : "bg-red-500";
        $badge_text = $camera['stok'] > 0 ? "Tersedia" : "Habis";
        
    } else {
        header("Location: index.html");
        exit();
    }
    
    $conn->close();
    ?>
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="index.html" class="flex items-center">
                        <i class="fas fa-camera text-red-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Rental<span class="text-red-600">Kamera</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-red-600 font-medium">Kembali</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column - Product Image -->
            <div class="lg:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-4">
                   <img 
    src="<?php echo !empty($camera['gambar']) 
        ? '../admin/uploads/' . $camera['gambar'] 
        : 'uploads/default.jpg'; ?>" 

    alt="<?php echo $camera['nama']; ?>" 
    class="w-full h-auto rounded-lg"
>

                </div>
            </div>

            <!-- Right Column - Product Info -->
            <div class="lg:w-1/2">
                <!-- Product Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="<?php echo $badge_color; ?> text-white text-sm font-semibold px-3 py-1 rounded-full">
                            <?php echo $badge_text; ?>
                        </span>
                        <span class="bg-gray-200 text-gray-800 text-sm font-semibold px-3 py-1 rounded-full">
                            <?php echo $camera['tipe']; ?>
                        </span>
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                            <?php echo $camera['merk']; ?>
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?php echo $camera['nama']; ?></h1>
                    
                    <div class="price-badge mb-6">
                        <?php echo $formatted_price; ?>.000 <span class="text-sm font-normal">/ hari</span>
                    </div>
                    
                    <div class="flex items-center mb-6">
                        <div class="flex items-center mr-6">
                            <i class="fas fa-box text-gray-600 mr-2"></i>
                            <span>Stok: <strong><?php echo $camera['stok']; ?> unit</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Spesifikasi -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Spesifikasi Kamera</h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="spec-card bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-camera text-red-600 mr-3"></i>
                                    <span class="font-semibold">Resolusi</span>
                                </div>
                                <p class="text-gray-700"><?php echo $camera['resolusi']; ?></p>
                            </div>
                            
                            <div class="spec-card bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-battery-full text-red-600 mr-3"></i>
                                    <span class="font-semibold">Baterai</span>
                                </div>
                                <p class="text-gray-700"><?php echo $camera['baterai']; ?></p>
                            </div>
                            
                            <div class="spec-card bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-weight text-red-600 mr-3"></i>
                                    <span class="font-semibold">Berat</span>
                                </div>
                                <p class="text-gray-700"><?php echo $camera['berat']; ?></p>
                            </div>
                            
                            <div class="spec-card bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-layer-group text-red-600 mr-3"></i>
                                    <span class="font-semibold">Tipe</span>
                                </div>
                                <p class="text-gray-700"><?php echo $camera['tipe']; ?></p>
                            </div>

                           <a href="pemesanan.php?kamera_id=<?php echo $camera['id']; ?>" 
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    Pesan
</a>

                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-8 pb-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2023 RentalKamera. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize date picker
        document.addEventListener('DOMContentLoaded', function() {
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start-date').min = today;
            document.getElementById('start-date').value = today;
            
            // Calculate initial total
            calculateTotal();
        });
        
        // Calculate total price
        function calculateTotal() {
            const duration = parseInt(document.getElementById('duration').value) || 1;
            const pricePerDay = <?php echo $camera['harga_sewa']; ?>;
            const total = pricePerDay * duration;
            
            // Update display
            document.getElementById('duration-display').textContent = duration + ' hari';
            document.getElementById('total-price').textContent = formatCurrency(total);
        }
        
        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        // Event listeners
        document.getElementById('duration').addEventListener('input', calculateTotal);
        
        // Submit booking
        function submitBooking() {
            const duration = parseInt(document.getElementById('duration').value);
            const startDate = document.getElementById('start-date').value;
            const cameraId = document.getElementById('camera-id').value;
            const cameraName = document.getElementById('camera-name').value;
            
            if (!startDate) {
                alert('Silakan pilih tanggal mulai sewa.');
                return false;
            }
            
            // Create booking object
            const booking = {
                cameraId: cameraId,
                cameraName: cameraName,
                duration: duration,
                startDate: startDate,
                pricePerDay: <?php echo $camera['harga_sewa']; ?>,
                total: <?php echo $camera['harga_sewa']; ?> * duration
            };
            
            // Save to localStorage
            let bookings = JSON.parse(localStorage.getItem('cameraBookings')) || [];
            bookings.push(booking);
            localStorage.setItem('cameraBookings', JSON.stringify(bookings));
            
            alert('Kamera ' + cameraName + ' telah ditambahkan ke keranjang!');
            
            // Redirect to cart page (if exists)
            // window.location.href = 'cart.html';
            
            return false; // Prevent form submission
        }
        
        // WhatsApp consultation
        function consultWhatsApp() {
            const cameraName = document.getElementById('camera-name').value;
            const message = `Halo, saya ingin konsultasi tentang kamera ${cameraName} (ID: <?php echo $camera['id']; ?>).`;
            const phone = '6281234567890';
            const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>