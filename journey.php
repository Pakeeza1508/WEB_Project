<?php
include "db.php";
include "auth_check.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Journey | LuxeStore</title>

    <!-- External Libraries -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --accent: #c084fc;
            --dark: #0f172a;
            --slate: #1e293b;
            --glass: rgba(255, 255, 255, 0.03);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--dark);
            color: white;
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* --- HERO --- */
        .hero {
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)),
                        url('https://images.pexels.com/photos/19416854/pexels-photo-19416854.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        .hero h1 {
            font-size: 5rem;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.7;
            max-width: 600px;
            font-weight: 300;
            letter-spacing: 1px;
        }

        /* --- SECTION --- */
        .section {
            padding: 120px 12%;
            position: relative;
        }

        /* --- STORY GRID --- */
        .story {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .story h2 {
            font-size: 3.5rem;
            margin-bottom: 25px;
            line-height: 1.1;
        }

        .story p {
            font-size: 1.1rem;
            color: #94a3b8;
            margin-bottom: 20px;
        }

        .story-img-container {
            position: relative;
            z-index: 1;
        }

        .story img {
            width: 100%;
            border-radius: 30px;
            filter: grayscale(30%);
            transition: 0.5s;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .story-img-container:hover img {
            filter: grayscale(0);
            transform: scale(1.02);
        }

        .story-img-container::after {
            content: "";
            position: absolute;
            top: -20px; right: -20px; width: 100%; height: 100%;
            border: 2px solid var(--primary);
            border-radius: 30px;
            z-index: -1;
            transition: 0.4s;
        }

        /* --- TIMELINE --- */
        .timeline {
            margin-top: 80px;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
            padding-left: 50px;
            position: relative;
        }

        .step {
            margin-bottom: 80px;
            position: relative;
        }

        .step::before {
            content: "";
            width: 20px;
            height: 20px;
            background: var(--primary);
            border: 4px solid var(--dark);
            border-radius: 50%;
            position: absolute;
            left: -61px;
            top: 5px;
            box-shadow: 0 0 20px var(--primary);
        }

        .step h3 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .step p {
            font-size: 1.1rem;
            color: #cbd5e1;
            max-width: 500px;
        }

        /* --- VIDEO --- */
        .video {
            background: var(--slate);
            border-radius: 40px;
            padding: 80px 20px;
            text-align: center;
        }

        .video-container {
            position: relative;
            width: 90%;
            max-width: 900px;
            margin: 40px auto 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        }

        .video iframe {
            width: 100%;
            aspect-ratio: 16/9;
            border: none;
        }

        /* --- BUTTON --- */
        .btn {
            display: inline-block;
            margin-top: 30px;
            padding: 16px 45px;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            transition: 0.4s;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
        }

        /* --- DECORATIVE ELEMENTS --- */
        .glow-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent);
            z-index: -1;
            border-radius: 50%;
        }

    </style>
</head>

<body>

<!-- HERO -->
<section class="hero">
    <h1 data-aos="zoom-out">Our Legacy.</h1>
    <p data-aos="fade-up" data-aos-delay="300">
        Merging artisan craftsmanship with modern digital innovation.
    </p>
    <div style="position: absolute; bottom: 30px; opacity: 0.5; animation: bounce 2s infinite;">
        <i class="fa fa-chevron-down"></i>
    </div>
</section>

<!-- STORY SECTION -->
<section class="section">
    <div class="glow-circle" style="top: 10%; right: 5%;"></div>
    
    <div class="story">
        <div data-aos="fade-right">
            <h2>Crafting the <br><em>Future</em> of Fashion</h2>
            <p>
                LuxeStore was never just about selling products. It was born from a desire to bridge the gap between high-end fashion boutiques and the convenience of modern technology.
            </p>
            <p>
                We believe that every item you wear tells a story. That’s why we meticulously vet every brand on our platform, ensuring they align with our pillars of <strong>Quality</strong>, <strong>Ethics</strong>, and <strong>Timeless Design</strong>.
            </p>
            <a href="shop.php" class="btn">Experience the Store</a>
        </div>

        <div class="story-img-container" data-aos="fade-left">
            <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=800" alt="Innovation">
        </div>
    </div>
</section>

<!-- TIMELINE -->
<section class="section" style="background: rgba(255,255,255,0.01);">
    <div class="glow-circle" style="bottom: 0; left: 0;"></div>
    
    <h2 data-aos="fade-up" style="text-align: center; font-size: 3.5rem; margin-bottom: 60px;">Milestones of <span>Growth</span></h2>

    <div class="timeline">
        <div class="step" data-aos="fade-up">
            <h3>2023 / The Spark</h3>
            <p>LuxeStore was conceptualized in a small design studio with a goal to revolutionize how luxury is perceived online.</p>
        </div>

        <div class="step" data-aos="fade-up" data-aos-delay="100">
            <h3>2024 / The Debut</h3>
            <p>We launched our invitation-only beta, bringing curated apparel to our first 10,000 fashion enthusiasts.</p>
        </div>

        <div class="step" data-aos="fade-up" data-aos-delay="200">
            <h3>2025 / Evolution</h3>
            <p>Today, we represent over 150 global brands, shipping luxury experiences to customers in over 40 countries.</p>
        </div>

        <div class="step" data-aos="fade-up" data-aos-delay="300">
            <h3>Future / Unlimited</h3>
            <p>Our journey is just beginning. We are exploring AI-driven personal styling and eco-luxury packaging initiatives.</p>
        </div>
    </div>
</section>

<!-- VIDEO SECTION -->
<section class="section">
    <div class="video" data-aos="fade-up">
        <h2>A Glimpse Into Our World</h2>
        <p style="opacity:0.7; max-width: 600px; margin: 0 auto;">
            Experience the culture behind LuxeStore. Where passion meets precision.
        </p>

        <div class="video-container" data-aos="zoom-in" data-aos-delay="200">
            <!-- Replace 'VIDEO_ID' with your YouTube/Vimeo ID -->
            <iframe 
                src="fashion.mp4" 
                title="LuxeStore Story"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

<footer style="text-align: center; padding: 50px; opacity: 0.4; font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.05);">
    &copy; 2025 LUXESTORE PREMIUM CO. ALL RIGHTS RESERVED.
</footer>

<script>
    AOS.init({ 
        duration: 1200, 
        once: true,
        easing: 'ease-out-back'
    });
</script>

<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
</style>

</body>
</html>