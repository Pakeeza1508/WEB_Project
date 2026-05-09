<?php
include "db.php";
include "auth_check.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>LuxeStore | Premium Ecommerce Experience</title>

    <!-- External Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background: var(--dark);
            color: white;
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(15px);
            padding: 15px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-size: 14px;
            transition: 0.3s;
            opacity: 0.8;
        }

        .nav-links a:hover {
            opacity: 1;
            color: var(--primary);
        }

        /* --- MODERN HERO SECTION --- */
        .hero {
            height: 95vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.8)),
                url('https://cdn.prod.website-files.com/605826c62e8de87de744596e/6762d5041de6287f9ddb3b3b_screenshot-2024-12-18-at-72250-pm-6762d47e79c21.webp');
            background-size: cover;
            background-position: center;
            z-index: -1;
            animation: kenburns 20s infinite alternate;
        }

        @keyframes kenburns {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.1);
            }
        }

        .hero-content {
            z-index: 10;
            max-width: 850px;
            padding: 20px;
        }

        .badge-new {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 12px;
            letter-spacing: 2px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: 4.5rem;
            line-height: 1.1;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(to bottom, #fff 60%, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 35px;
            color: #cbd5e1;
        }

        .btn {
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.4s;
            text-decoration: none;
            font-size: 15px;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn:hover {
            transform: translateY(-5px);
            background: var(--secondary);
            color: white;
        }

        /* --- BRAND SLIDER --- */
        .brand-section {
            background: white;
            padding: 40px 0;
            overflow: hidden;
            display: flex;
        }

        .brand-track {
            display: flex;
            width: calc(200px * 20);
            animation: scroll 40s linear infinite;
        }

        .brand-track img {
            width: 150px;
            height: 50px;
            object-fit: contain;
            margin: 0 50px;
            filter: grayscale(100%);
            opacity: 0.5;
            transition: 0.3s;
        }

        .brand-track img:hover {
            filter: grayscale(0);
            opacity: 1;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(-200px * 10));
            }
        }

        /* --- ABOUT US --- */
        .about-section {
            padding: 100px 8%;
            display: flex;
            align-items: center;
            gap: 80px;
        }

        .about-img {
            flex: 1;
        }

        .about-img img {
            width: 100%;
            border-radius: 30px;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .about-text {
            flex: 1;
        }

        .about-text h2 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .about-text h2 span {
            color: var(--primary);
        }

        .about-text p {
            font-size: 1.1rem;
            opacity: 0.7;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .stats {
            display: flex;
            gap: 30px;
            margin-top: 40px;
        }

        .stat-item h3 {
            font-size: 2rem;
            color: var(--primary);
        }

        /* --- COLLECTIONS & TABS --- */
        .section {
            padding: 90px 8%;
        }

        .title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
        }

        .tab-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .tab-btn {
            padding: 12px 30px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
            font-weight: 600;
        }

        .tab-btn.active,
        .tab-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        }

        /* --- UNIFORM PRODUCT GRID --- */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-item {
            transition: transform 0.4s ease, opacity 0.4s ease;
        }

        .card {
            background: var(--glass);
            padding: 20px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            transition: 0.4s;
            display: flex;
            flex-direction: column;
            height: 100%;
            /* Ensures all cards in a row have equal height */
        }

        .card:hover {
            transform: translateY(-15px);
            border-color: var(--primary);
        }

        /* UNIFORM WHITE IMAGE BOX */
        .card-img-container {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: white;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            transition: 0.5s;
        }

        .card-overlay {
            position: absolute;
            top: 10px;
            right: -50px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: 0.4s;
        }

        .card:hover .card-overlay {
            right: 10px;
        }

        .icon-btn {
            background: #eee;
            color: var(--dark);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: var(--primary);
            color: white;
        }

        /* FIX: Badge Positioning & Shape */
        .card-img-container .badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            font-size: 9px;
            font-weight: 700;
            border-radius: 999px;
            color: white;
            z-index: 20;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            width: auto;
            max-width: calc(100% - 24px);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.22);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-img-container .badge.new {
            background: #10b981;
        }

        .card-img-container .badge.trend {
            background: #a855f7;
        }

        .card-img-container .badge.best {
            background: #f59e0b;
        }

        .card:hover .card-img-container .badge {
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(0, 0, 0, 0.28);
        }

        .card-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 15px;
        }

        .price {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .add-cart-btn {
            background: var(--primary);
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-cart-btn:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }

        /* --- FLASH SALE --- */
        .flash-sale-section {
            margin: 100px 8%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 40px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
        }

        .flash-content {
            display: flex;
            align-items: center;
        }

        .flash-text {
            flex: 1;
            padding: 60px;
        }

        .flash-img {
            flex: 1;
            height: 100%;
        }

        .flash-img img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .countdown-timer {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }

        .time-block {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            min-width: 80px;
        }

        .time-block span {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: var(--primary);
        }

        .time-block small {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.6;
        }

        .why-us {
            position: relative;
            padding: 100px 8%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.05), transparent);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .feature-card {
            position: relative;
            background: var(--glass);
            padding: 50px 30px;
            border-radius: 30px;
            border: 1px solid var(--glass-border);
            text-align: center;
            transition: 0.5s cubic-bezier(0.2, 1, 0.3, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        /* Floating Animation */
        .feature-card:hover {
            transform: translateY(-20px) scale(1.02);
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            transition: 0.5s;
        }

        .feature-card:hover .icon-wrapper {
            transform: rotateY(360deg);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .feature-card p {
            font-size: 0.95rem;
            opacity: 0.6;
            line-height: 1.6;
        }

        /* Glowing background effect on hover */
        .card-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0%;
            height: 0%;
            background: var(--primary);
            filter: blur(100px);
            opacity: 0;
            transition: 0.6s;
            z-index: -1;
            transform: translate(-50%, -50%);
        }

        .feature-card:hover .card-glow {
            width: 150%;
            height: 150%;
            opacity: 0.15;
        }

        /* Feature Badge */
        .feature-badge {
            margin-top: 25px;
            padding: 5px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--primary);
            font-weight: 700;
        }



        .catalog-gateway {
            padding: 0;
            position: relative;
            height: 70vh;
            display: flex !important;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }

        .gateway-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)),
                url('https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1500');
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: blur(5px);
        }

        .gateway-content {
            max-width: 700px;
            z-index: 10;
            position: relative;
            color: white;
            text-align: center;
        }

        .gateway-content h2 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <!-- <nav class="navbar">
        <a href="#" class="logo">LUXE<span>STORE</span></a>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#about">About</a>
            <a href="#shop-collections">Shop</a>
            <a href="logout.php"><i class="fa fa-user"></i> Logout</a>
            <a href="#" style="background: var(--primary); padding: 8px 15px; border-radius: 10px; color:white;">
                <i class="fa fa-shopping-cart"></i>
            </a>
             
        </div>
    </nav> -->

    <?php include "navbar.php"; ?>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <div class="badge-new"> 2025 Summer Collection</div>
            <h1 data-aos="fade-up">Elevate Your Lifestyle With LuxeStore</h1>
            <p data-aos="fade-up" data-aos-delay="200">
                Experience the pinnacle of fashion and luxury. We curate the finest brands
                globally to ensure you stay ahead of the curve.
            </p>
            <div class="hero-btns" data-aos="fade-up" data-aos-delay="400">
                <a href="#shop-collections" class="btn btn-primary">Shop Now</a>
                <a href="#shop-collections" class="btn btn-outline">Explore Deals</a>
            </div>
        </div>
    </section>

    <!-- BRAND BAR -->
    <div class="brand-section">
        <div class="brand-track">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnBKgsCqfDNVkO6aC-qlnSVUCUkxErNsV6Tw&s"
                alt="Nike">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" alt="Adidas">
            <img src="https://upload.wikimedia.org/wikipedia/en/d/da/Puma_complete_logo.svg" alt="Puma">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZ1qrQ0XNyIKBlawdJVGYpoivCO3NQmJ4R_Q&s"
                alt="Reebok">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/Converse_logo.svg?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original"
                alt="Converse">
            <img src="https://logomakerr.ai/blog/wp-content/uploads/2022/08/2019-to-Present-Zara-logo-design.jpg"
                alt="Zara">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRUG6gerrQ_s1CKmZW0PiQFqFFRrLbO6N6OYw&s"
                alt="H&M">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_wQslyLHHWadGSZtVa8qt_p1-frMXfDIt8A&s"
                alt="Uniqlo">
            <img src="https://fabrikbrands.com/wp-content/uploads/Levis-Logo-History-1b.png" alt="Levis">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTzuk3Zkmg0VAAmbclHTA70lD6E9DMqiRe57Q&s"
                alt="Calvin Klein">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTTw8kPOSsjnMqIciX_VxAR5yd7XmbIF0ZXQ&s"
                alt="Tommy Hilfiger">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSY8Pyllnp6Zu_X5lYOrVZywH2R-k0S-NI09A&s"
                alt="Gucci">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/76/Louis_Vuitton_logo_and_wordmark.svg/250px-Louis_Vuitton_logo_and_wordmark.svg.png"
                alt="Louis Vuitton">
            <img src="https://cdn.worldvectorlogo.com/logos/prada-5.svg" alt="Prada">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQcu2dZX3v0iVujkD8rywTXl2iFcL14aFqLLA&s"
                alt="Michael Kors">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxIIdkY8iUiOXNkctum-ghnCcfLfxfPxPlEg&s"
                alt="RayBan">
        </div>
    </div>


    <!-- ABOUT US SECTION -->
    <section id="about" class="about-section">
        <div class="about-img" data-aos="fade-right">
            <img src="https://images.unsplash.com/photo-1574634534894-89d7576c8259?q=80&w=764&auto=format&fit=crop"
                alt="About Us">
        </div>
        <div class="about-text" data-aos="fade-left">
            <h2>Where <span>Fashion Meets Innovation</span></h2>
            <p>
                At LuxeStore, we transform online shopping into a premium lifestyle experience.
                Our platform brings together world-class fashion, modern trends, and timeless elegance.
            </p>
            <div class="stats">
                <div class="stat-item">
                    <h3>120K+</h3>
                    <p>Customers</p>
                </div>
                <div class="stat-item">
                    <h3>200+</h3>
                    <p>Brands</p>
                </div>
                <div class="stat-item">
                    <h3>10K+</h3>
                    <p>Products</p>
                </div>
            </div>
            <br>
            <a href="journey.php" class="btn btn-outline">Discover Our Journey</a>
        </div>
    </section>

    <!-- SECTION: DISCOVER COLLECTIONS -->
    <section id="shop-collections" class="section">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 50px;">
                <h2 class="title" data-aos="fade-down" style="font-size: 3rem; margin-bottom: 10px;">
                    Our <span>Collections</span>
                </h2>
                <div class="tab-container" data-aos="fade-up">
                    <button class="tab-btn active" onclick="filterTab('new', this)">New Arrivals</button>
                    <button class="tab-btn" onclick="filterTab('trending', this)">Trending</button>
                    <button class="tab-btn" onclick="filterTab('bestseller', this)">Bestsellers</button>
                </div>
            </div>

            <div class="grid" id="product-grid">
                <?php
                $res = $conn->query("SELECT * FROM products");
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $tag = $row['tag'];
                        ?>
                        <div class="card product-item <?= $tag ?>" data-aos="zoom-in">
                            <div class="card-img-container">
                                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                                <div class="card-overlay">
                                    <a href="wishlist_logic.php?add_wish=<?= $row['pid'] ?>&type=featured" class="icon-btn">
                                        <i class="fa-regular fa-heart"></i>
                                    </a>
                                    <button class="icon-btn"><i class="fa fa-share-alt"></i></button>
                                </div>
                                <?php if ($tag == 'trending'): ?><span class="badge trend">TRENDING</span>
                                <?php elseif ($tag == 'bestseller'): ?><span class="badge best">BESTSELLER</span>
                                <?php else: ?><span class="badge new">NEW</span><?php endif; ?>
                            </div>
                            <div class="card-info">
                                <p class="cat-name"
                                    style="font-size: 11px; color: var(--primary); margin-top:10px; font-weight:600;">
                                    <?= strtoupper($row['category']) ?>
                                </p>
                                <h3 style="font-size: 1.1rem; margin: 5px 0; font-weight:600;"><?= $row['name'] ?></h3>
                                <div class="price-row">
                                    <p class="price">Rs <?= number_format($row['price']) ?></p>
                                    <form method="post" action="add_to_cart.php">
                                        <input type="hidden" name="pid" value="<?= $row['pid'] ?>">
                                        <button type="submit" class="add-cart-btn">
                                            <i class="fa fa-shopping-bag"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </section>

    <!-- FLASH SALE SECTION -->
    <section class="flash-sale-section" data-aos="fade-up">
        <div class="flash-content">
            <div class="flash-text">
                <span style="color:var(--primary); font-weight:600;">LIMITED OFFER</span>
                <h2>Flash Sale <span>Live Now</span></h2>
                <div class="countdown-timer">
                    <div class="time-block"><span id="days">00</span><small>Days</small></div>
                    <div class="time-block"><span id="hours">00</span><small>Hours</small></div>
                    <div class="time-block"><span id="mins">00</span><small>Mins</small></div>
                    <div class="time-block"><span id="secs">00</span><small>Secs</small></div>
                </div>
                <a href="#shop-collections" class="btn btn-primary">Grab Deals</a>
            </div>
            <div class="flash-img">
                <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=1000&auto=format&fit=crop"
                    alt="Sale Image">
            </div>
        </div>
    </section>

    <!-- SECTION: WHY CHOOSE US -->
    <section class="section why-us">
        <div class="container">
            <h2 class="title" data-aos="fade-down">The LuxeStore <span>Experience</span></h2>

            <div class="features-grid">
                <!-- Feature 1: Free Shipping -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-glow"></div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3>Free Shipping</h3>
                    <p>Enjoy complimentary shipping on all premium orders over Rs 5,000. Worldwide tracking included.
                    </p>
                    <div class="feature-badge">Reliable</div>
                </div>

                <!-- Feature 2: Secure Payment -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-glow"></div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>Secure Payment</h3>
                    <p>Your transactions are shielded by AES-256 bit encryption. Shop with 100% peace of mind.</p>
                    <div class="feature-badge">Verified</div>
                </div>

                <!-- Feature 3: Easy Returns -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-glow"></div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    <h3>Easy Returns</h3>
                    <p>Not satisfied? No problem. Our 30-day hassle-free return policy ensures you always get the best.
                    </p>
                    <div class="feature-badge">Flexible</div>
                </div>

                <!-- Feature 4: 24/7 Support -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-glow"></div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated luxury concierge team is available around the clock for any assistance you need.
                    </p>
                    <div class="feature-badge">Expert</div>
                </div>
                <!-- Feature 5: Fast Delivery -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-glow"></div>

                    <div class="icon-wrapper">
                        <i class="fa-solid fa-bolt"></i>
                    </div>

                    <h3>Fast Delivery</h3>

                    <p>
                        Get your fashion items delivered in record time with our express shipping service across major
                        cities.
                    </p>

                    <div class="feature-badge">Express</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: PRODUCT CATALOG GATEWAY -->
    <section class="section catalog-gateway">
        <div class="gateway-bg"></div>
        <div class="gateway-content aos-animate">
            <h2>Explore The Full Collection</h2>
            <p>Dive into our complete catalog with advanced filters, sorting, and everything you need to find your
                perfect item.</p>
            <a href="all-products.php" class="btn btn-primary">View All Products</a>
        </div>
    </section>

    <section>
        <?php include "reviews_section.php"; ?>
    </section>

<?php include "footer.php"; ?>


    <script>
        AOS.init({ duration: 1000, once: true });

        function filterTab(tag, btn) {
            const btns = document.querySelectorAll('.tab-btn');
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const products = document.querySelectorAll('.product-item');
            products.forEach(product => {
                product.style.opacity = '0';
                product.style.transform = 'scale(0.8)';

                setTimeout(() => {
                    if (product.classList.contains(tag)) {
                        product.style.display = 'flex'; // Changed to flex to support card layout
                        setTimeout(() => {
                            product.style.opacity = '1';
                            product.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        product.style.display = 'none';
                    }
                }, 300);
            });
        }

        window.onload = function () {
            const activeBtn = document.querySelector('.tab-btn.active');
            if (activeBtn) filterTab('new', activeBtn);
        };

        const countdownDate = new Date().getTime() + (24 * 60 * 60 * 1000);
        setInterval(() => {
            const now = new Date().getTime();
            const distance = countdownDate - now;
            document.getElementById("days").innerText = Math.floor(distance / (1000 * 60 * 60 * 24));
            document.getElementById("hours").innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            document.getElementById("mins").innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            document.getElementById("secs").innerText = Math.floor((distance % (1000 * 60)) / 1000);
        }, 1000);
    </script>
</body>

</html>