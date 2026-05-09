<?php
include "db.php";
include "auth_check.php";

// Fetch all products and group them by category in a PHP array
$res = $conn->query("SELECT * FROM shop_products ORDER BY category ASC");
$grouped_products = [];
while ($row = $res->fetch_assoc()) {
    $grouped_products[$row['category']][] = $row;
}

// Icon Mapping for Headers
$icons = [
    "Shoes" => "fa-solid fa-shoe-prints",
    "Fashion" => "fa-solid fa-shirt",
    "Sunglasses" => "fa-solid fa-glasses",
    "Accessories" => "fa-solid fa-gem",
    "" => "fa-solid fa-laptop"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boutique Catalog | LuxeStore</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --glass: rgba(255, 255, 255, 0.05); --glass-border: rgba(255, 255, 255, 0.1); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background: var(--dark); color: white; overflow-x: hidden; }

        /* --- NAVBAR --- */
        .navbar { position: sticky; top: 0; z-index: 1000; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(15px); padding: 15px 6%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); }
        .logo { font-size: 24px; font-weight: 700; color: white; text-decoration: none; }
        .logo span { color: var(--primary); }
        .nav-links a { color: white; text-decoration: none; margin-left: 25px; font-size: 14px; }

        /* --- LAYOUT --- */
        .shop-container { display: grid; grid-template-columns: 280px 1fr; gap: 40px; padding: 40px 6%; }

        /* --- SIDEBAR (Jump Menu) --- */
        .sidebar { background: var(--glass); border: 1px solid var(--glass-border); border-radius: 30px; padding: 30px; height: fit-content; position: sticky; top: 100px; }
        .filter-group { margin-bottom: 30px; }
        .filter-group h3 { font-size: 0.9rem; margin-bottom: 15px; border-bottom: 1px solid var(--glass-border); padding-bottom: 8px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; }
        .search-box input { width: 100%; padding: 12px; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; outline: none; }
        .cat-list { list-style: none; }
        .cat-list li a { padding: 10px 0; color: #94a3b8; text-decoration: none; display: block; transition: 0.3s; font-size: 14px; }
        .cat-list li a:hover { color: var(--primary); transform: translateX(5px); }

        /* --- CATEGORY SECTIONING --- */
        .category-section { margin-bottom: 80px; }
        .category-title { 
            display: flex; align-items: center; gap: 15px; margin-bottom: 30px; 
            padding-bottom: 15px; border-bottom: 1px solid var(--glass-border);
        }
        .category-title i { background: var(--primary); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .category-title h2 { font-size: 2rem; font-weight: 600; text-transform: capitalize; }
        .category-title span { font-size: 1rem; opacity: 0.4; font-weight: 300; margin-left: auto; }

        /* Floating Wishlist Button */
.wishlist-btn-float {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 35px;
    height: 35px;
    background: white;
    color: #333;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    z-index: 10;
    transition: 0.3s;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.wishlist-btn-float:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

/* Ensure the img-box is relative so the button sticks to it */
.img-box {
    position: relative;
    background: white;
    border-radius: 20px;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
        /* --- GRID & CARDS --- */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }
        .card { background: var(--glass); border-radius: 24px; border: 1px solid var(--glass-border); padding: 18px; transition: 0.4s; height: 100%; display: flex; flex-direction: column; }
        .card:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 15px 35px rgba(0,0,0,0.4); }
        
        /* UNIFORM WHITE IMAGE BOX (Matches your previous request) */
        .img-box { background: white; border-radius: 20px; height: 220px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; margin-bottom: 15px; }
        .img-box img { max-width: 85%; max-height: 85%; object-fit: contain; transition: 0.5s; }
        .card:hover img { transform: scale(1.1); }

        .badge-discount { position: absolute; top: 12px; left: 12px; background: #ef4444; color: white; font-size: 10px; padding: 5px 10px; border-radius: 8px; font-weight: bold; }
        
        .info { flex-grow: 1; }
        .info h3 { font-size: 1rem; font-weight: 600; margin-bottom: 8px; color: #fff; }
        .stars { color: #fbbf24; font-size: 11px; margin-bottom: 12px; }

        .price-row { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .price { font-size: 1.2rem; font-weight: 700; color: var(--primary); }
        .add-btn { background: var(--primary); color: white; border: none; width: 40px; height: 40px; border-radius: 12px; cursor: pointer; transition: 0.3s; font-size: 1.1rem; }
        .add-btn:hover { background: #4f46e5; transform: rotate(15deg); }

        /* --- FILTERS (Logic) --- */
        .hidden-section { display: none; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="shop.php" class="logo">LUXE<span>STORE</span></a>
    <div class="nav-links">
        <a href="shop.php">Home</a>
        <a href="logout.php">Logout</a>
        <a href="#" style="background: var(--primary); padding: 8px 18px; border-radius: 10px;"><i class="fa fa-shopping-cart"></i></a>
    </div>
</nav>

<div class="shop-container">
    <!-- SIDEBAR JUMP MENU -->
    <aside class="sidebar" data-aos="fade-right">
        <div class="filter-group">
            <h3>Search Catalog</h3>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Find product..." onkeyup="applyFilters()">
            </div>
        </div>

        <div class="filter-group">
            <h3>Quick Navigation</h3>
            <ul class="cat-list">
                <?php foreach(array_keys($grouped_products) as $cat): ?>
                <li><a href="#section-<?= $cat ?>"><?= $cat ?> Collection</a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="filter-group">
            <h3>Budget Limit</h3>
            <input type="range" min="1000" max="300000" value="300000" class="price-slider" id="priceSlider" oninput="applyFilters()">
            <div style="display:flex; justify-content: space-between; font-size: 11px; margin-top: 10px; opacity: 0.6;">
                <span>Min: 1k</span>
                <span id="priceLabel">Max: 300k</span>
            </div>
        </div>
    </aside>

    <!-- MAIN PRODUCT CATALOG -->
    <main>
        <?php foreach ($grouped_products as $category => $products): ?>
            <section class="category-section" id="section-<?= $category ?>" data-aos="fade-up">
                <div class="category-title">
                    <i class="<?= $icons[$category] ?? 'fa-solid fa-layer-group' ?>"></i>
                    <h2><?= $category ?> <span>Collection</span></h2>
                    <span><?= count($products) ?> Products</span>
                </div>

                <div class="grid">
                    <?php foreach ($products as $row): ?>
                        <div class="card catalog-item" 
                             data-price="<?= $row['price'] ?>" 
                             data-name="<?= strtolower($row['name']) ?>">
                            <div class="img-box">
                                <!-- 1. WISHLIST LINK ADDED HERE -->
                <a href="wishlist_logic.php?add_wish=<?= $row['spid'] ?>&type=catalog" class="wishlist-btn-float">
                    <i class="fa-regular fa-heart"></i>
                </a>
                                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                                <?php if($row['discount'] > 0): ?>
                                    <div class="badge-discount">-<?= $row['discount'] ?>% OFF</div>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <h3><?= $row['name'] ?></h3>
                                <div class="stars">
                                    <?php for($i=0; $i<floor($row['rating']); $i++) echo '<i class="fa fa-star"></i>'; ?>
                                    <span style="color:grey; font-size:10px; margin-left: 5px;">(<?= $row['rating'] ?>)</span>
                                </div>
                                <div class="price-row">
                                    <span class="price">Rs <?= number_format($row['price']) ?></span>
                                    <form method="post" action="add_to_cart.php">
    <input type="hidden" name="spid" value="<?= $row['spid'] ?>">
    <button type="submit" class="add-btn">
        <i class="fa-solid fa-cart-plus"></i>
    </button>
</form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
</div>

<script>
    AOS.init({ duration: 800, once: true });

    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const maxPrice = parseInt(document.getElementById('priceSlider').value);
        const sections = document.querySelectorAll('.category-section');
        
        document.getElementById('priceLabel').innerText = "Max: Rs " + (maxPrice / 1000) + "k";

        sections.forEach(section => {
            const items = section.querySelectorAll('.catalog-item');
            let visibleCount = 0;

            items.forEach(item => {
                const itemPrice = parseInt(item.getAttribute('data-price'));
                const itemName = item.getAttribute('data-name');

                const matchPrice = (itemPrice <= maxPrice);
                const matchSearch = (itemName.includes(search));

                if (matchPrice && matchSearch) {
                    item.style.display = "flex";
                    visibleCount++;
                } else {
                    item.style.display = "none";
                }
            });

            // Hide the entire category section (header included) if no products match
            if (visibleCount === 0) {
                section.classList.add('hidden-section');
            } else {
                section.classList.remove('hidden-section');
            }
        });
    }
</script>

</body>
</html>