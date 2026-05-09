<?php
// 1. FETCH PRODUCTS FOR REVIEW DROPDOWN
$review_products = [];
$product_query = "SELECT spid, name FROM shop_products ORDER BY name ASC";
$product_res = $conn->query($product_query);
if ($product_res) {
    while ($row = $product_res->fetch_assoc()) {
        $review_products[] = $row;
    }
}

// 2. HANDLE REVIEW SUBMISSION
if (isset($_POST['submit_review'])) {
    $uid = (int) ($_SESSION['uid'] ?? 0);
    $spid = (int) ($_POST['spid'] ?? 0);
    $rating = (int) ($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');

    if ($uid > 0 && $spid > 0 && $comment !== '') {
        $stmt = $conn->prepare('INSERT INTO reviews (userid, spid, rating, comment, is_verified) VALUES (?, ?, ?, ?, 1)');
        $stmt->bind_param('iiis', $uid, $spid, $rating, $comment);
        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        title: 'Thank You!',
                        text: 'Your review has been posted.',
                        icon: 'success',
                        confirmButtonColor: '#6366f1'
                    }).then(() => { window.location.href = window.location.pathname; });
                  </script>";
        }
        $stmt->close();
    }
}

// 3. FETCH REAL REVIEWS FROM DB
$real_reviews = [];
$review_query = "SELECT r.*, u.username 
                 FROM reviews r 
                 JOIN users u ON r.userid = u.userid 
                 ORDER BY r.created_at DESC LIMIT 6";
$res = $conn->query($review_query);
if($res && $res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $real_reviews[] = $row;
    }
}

// 4. DUMMY REVIEWS ARRAY (Always visible)
$dummy_reviews = [
    [
        "username" => "Sophia Reynolds",
        "rating" => 5,
        "comment" => "Absolutely in love with the quality of the fabrics here. The shipping was incredibly fast and the packaging felt truly premium!",
        "is_verified" => 1,
        "created_at" => "2024-05-01 10:00:00"
    ],
    [
        "username" => "Marc Karson",
        "rating" => 4,
        "comment" => "Great customer support. I had an issue with my shoe size and the concierge team swapped it for me in less than 24 hours. Highly impressed.",
        "is_verified" => 1,
        "created_at" => "2024-04-28 14:20:00"
    ],
    [
        "username" => "Elena G.",
        "rating" => 5,
        "comment" => "The Zara and LV collections are curated so well. It’s hard to find such a smooth luxury shopping experience online. 10/10!",
        "is_verified" => 1,
        "created_at" => "2024-04-15 09:15:00"
    ]
];

// Combine them: Real reviews first, then dummies
$display_reviews = array_merge($real_reviews, $dummy_reviews);
?>

<section class="section reviews-section" id="reviews">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 class="title" data-aos="fade-down">Customer <span>Feedback</span></h2>
            <p style="opacity: 0.6;">Hear from our community of over 120,000+ happy shoppers.</p>
        </div>

        <div class="reviews-grid">
            <?php 
            // Loop through the merged array
            foreach($display_reviews as $rev): 
            ?>
            <div class="review-card" data-aos="fade-up">
                <div class="quote-icon"><i class="fa fa-quote-left"></i></div>
                
                <div class="user-meta">
                    <div class="user-img">
                        <?= strtoupper(substr($rev['username'], 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <h4><?= htmlspecialchars($rev['username']) ?></h4>
                        <?php if($rev['is_verified']): ?>
                            <span class="verified-badge"><i class="fa fa-check-circle"></i> Verified Buyer</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="star-rating">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fa-solid fa-star <?= ($i <= $rev['rating']) ? 'active' : 'inactive' ?>"></i>
                    <?php endfor; ?>
                </div>

                <p class="comment">"<?= htmlspecialchars($rev['comment']) ?>"</p>
                <span class="review-date"><?= date('M d, Y', strtotime($rev['created_at'])) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- WRITE A REVIEW BUTTON -->
        <div style="text-align: center; margin-top: 50px;">
            <button class="btn btn-outline" onclick="toggleReviewModal()">
                <i class="fa-solid fa-pen-to-square"></i> Write a Review
            </button>
        </div>
    </div>
</section>

<!-- MODAL: ADD REVIEW -->
<div id="reviewModal" class="review-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="toggleReviewModal()">&times;</span>
        <h2>Share Your <span>Experience</span></h2>
        <p style="opacity: 0.6; font-size: 0.9rem; margin-bottom: 20px;">Your feedback helps us improve our luxury experience.</p>
        
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <p style="margin-bottom: 8px;">Select Product:</p>
                <select name="spid" required style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 15px; padding: 15px; color: white; outline: none;">
                    <option value="">-- Choose a product --</option>
                    <?php foreach($review_products as $product): ?>
                        <option value="<?= htmlspecialchars($product['spid']) ?>"><?= htmlspecialchars($product['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="rating" id="ratingValue" value="5">
            <div class="rating-input">
                <p>Your Rating:</p>
                <div class="stars-selector">
                    <i class="fa-solid fa-star star-sel active" data-value="1"></i>
                    <i class="fa-solid fa-star star-sel active" data-value="2"></i>
                    <i class="fa-solid fa-star star-sel active" data-value="3"></i>
                    <i class="fa-solid fa-star star-sel active" data-value="4"></i>
                    <i class="fa-solid fa-star star-sel active" data-value="5"></i>
                </div>
            </div>
            <textarea name="comment" placeholder="Tell us what you liked..." required rows="4"></textarea>
            <button type="submit" name="submit_review" class="btn btn-primary" style="width: 100%;">Post Review</button>
        </form>
    </div>
</div>

<style>
/* --- STYLING (KEEPING YOUR EXISTING THEME) --- */
.reviews-section { background: radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.05), transparent); padding: 100px 8%; }
.reviews-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
.review-card { background: var(--glass); border: 1px solid var(--glass-border); padding: 35px; border-radius: 30px; position: relative; transition: 0.4s; backdrop-filter: blur(10px); height: 100%; display: flex; flex-direction: column; }
.review-card:hover { transform: translateY(-10px); border-color: var(--primary); background: rgba(255, 255, 255, 0.08); }
.quote-icon { position: absolute; top: 20px; right: 30px; font-size: 2rem; opacity: 0.1; color: var(--primary); }
.user-meta { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
.user-img { width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; }
.verified-badge { font-size: 0.7rem; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 4px; }
.star-rating { margin-bottom: 10px; }
.star-rating i.active { color: #fbbf24; }
.star-rating i.inactive { color: rgba(255,255,255,0.1); }
.comment { font-size: 0.95rem; line-height: 1.6; color: #cbd5e1; font-style: italic; flex-grow: 1; margin-bottom: 15px; }
.review-date { font-size: 0.8rem; opacity: 0.4; }

/* MODAL */
.review-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); }
.modal-content { background: #1e293b; margin: 10% auto; padding: 40px; border: 1px solid var(--glass-border); border-radius: 30px; width: 500px; position: relative; }
.close-btn { position: absolute; right: 25px; top: 20px; color: #fff; font-size: 28px; cursor: pointer; opacity: 0.5; }
.stars-selector { display: flex; gap: 10px; margin-top: 10px; font-size: 1.5rem; }
.star-sel { cursor: pointer; color: rgba(255,255,255,0.1); }
.star-sel.active { color: #fbbf24; }
textarea { width: 100%; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 15px; padding: 15px; color: white; margin-bottom: 20px; outline: none; }
</style>

<script>
    function toggleReviewModal() {
        const modal = document.getElementById('reviewModal');
        modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
    }

    document.querySelectorAll('.star-sel').forEach(star => {
        star.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            document.getElementById('ratingValue').value = val;
            document.querySelectorAll('.star-sel').forEach(s => {
                s.classList.toggle('active', s.getAttribute('data-value') <= val);
            });
        });
    });
</script>