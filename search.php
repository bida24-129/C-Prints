<?php
// search.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$results = [];
$infoResults = [];
$searchQuery = "";
$aiResponse = "";
$filter = $_GET['filter'] ?? 'all';

function highlightTerm($text, $term) {
    return preg_replace('/(' . preg_quote($term, '/') . ')/i', '<mark>$1</mark>', $text);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = htmlspecialchars($_POST['search']);
    header("Location: search.php?search=" . urlencode($searchQuery) . "&filter=all");
    exit();
}

if (isset($_GET['search'])) {
    $searchQuery = htmlspecialchars($_GET['search']);

    // AI assistant
    $apiKey = "your-api-key-here";
    $prompt = "You're an assistant for a Christian clothing brand called Style Fix. Based on the query: \"$searchQuery\", give helpful suggestions, product ideas, or guidance to a customer visiting the site.";
    $postData = [
        "model" => "gpt-3.5-turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "max_tokens" => 300,
        "temperature" => 0.7,
    ];
    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $response = curl_exec($ch);
    if(curl_errno($ch)){
        $aiResponse = "AI error: " . curl_error($ch);
    } else {
        $data = json_decode($response, true);
        $aiResponse = $data['choices'][0]['message']['content'] ?? '';
    }
    curl_close($ch);

    // Fetch info
    if ($filter === 'all' || $filter === 'links' || $filter === 'videos') {
        $sqlInfo = "SELECT * FROM info WHERE title LIKE '%$searchQuery%'";
        $resultInfo = $conn->query($sqlInfo);
        if ($resultInfo && $resultInfo->num_rows > 0) {
            while($row = $resultInfo->fetch_assoc()) {
                $infoResults[] = $row;
            }
        }
    }

    // Fetch images
    if ($filter === 'all' || $filter === 'images') {
        $sqlCollections = "SELECT collections.id, collections.name, collections.url, images.image 
                        FROM collections 
                        JOIN images ON collections.id = images.collection_id 
                        WHERE collections.name LIKE '%$searchQuery%'";
        $resultCollections = $conn->query($sqlCollections);
        if ($resultCollections && $resultCollections->num_rows > 0) {
            while($row = $resultCollections->fetch_assoc()) {
                $results[$row['id']]['url'] = $row['url'];
                $results[$row['id']]['images'][] = $row['image'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search - Style Fix</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="search.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>

<header>
    <nav>
        <a href="home.php" >Home</a> 
        <a href="about.html" >About</a> 
        <a href="service.html">Services</a> 
        <a href="FAQ.html">FAQ</a>
        <a href="testimonial.html">Testimonials</a>
        <a href="gallery.html">Gallery</a>
        <a href="order.html">Order</a> 
        <a href="cart.html">Cart</a>
        <a href="contact.html" >Contact</a> 
        <a href="myprofile.php">Profile</a>
        <a href="search.php" class="active">Search</a>
    </nav>
</header>

<div class="search-container">
    <form action="search.php" method="POST" class="position-relative">
        <div class="search-box">
            <input type="text" name="search" id="searchInput" autocomplete="on" placeholder="Search here..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </div>
        <div id="suggestions" class="suggestions-box d-none"></div>
    </form>
</div>

<?php if (!empty($searchQuery)): ?>
<div class="filters">
    <a href="?search=<?php echo urlencode($searchQuery); ?>&filter=all" class="<?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
    <a href="?search=<?php echo urlencode($searchQuery); ?>&filter=images" class="<?php echo $filter == 'images' ? 'active' : ''; ?>">Images</a>
    <a href="?search=<?php echo urlencode($searchQuery); ?>&filter=videos" class="<?php echo $filter == 'videos' ? 'active' : ''; ?>">Videos</a>
    <a href="?search=<?php echo urlencode($searchQuery); ?>&filter=links" class="<?php echo $filter == 'links' ? 'active' : ''; ?>">Links</a>
</div>
<?php endif; ?>

<?php if (!empty($searchQuery)): ?>
<div class="results-section">
    <div class="text-result">
        <h5>Here are your search results:</h5>
        <p><?php echo nl2br($aiResponse ?: "We couldn't generate suggestions for this query. Try searching with different keywords!"); ?></p>
    </div>

    <?php if ($filter === 'all' || $filter === 'links' || $filter === 'videos'): ?>
        <h4 style="margin-left: 50px;">Links</h4>
        <?php if (empty($infoResults)): ?>
            <p style="margin-left: 50px;">No results found.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($infoResults as $info): ?>
                    <div class="result-item">
                        <a href="<?php echo $info['url'] ?? '#'; ?>" target="_blank">
                            <strong><?php echo highlightTerm($info['title'], $searchQuery); ?></strong>
                        </a>
                        <div class="content"><?php echo highlightTerm($info['content'], $searchQuery); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($filter === 'all' || $filter === 'images'): ?>
        <h4 style="margin-left: 50px;">Images</h4>
        <?php if (empty($results)): ?>
            <p style="margin-left: 50px;">No results found.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($results as $result): ?>
                    <div class="result-item">
                        <?php foreach ($result['images'] as $img): ?>
                            <img loading="lazy" src="<?php echo $img; ?>" alt="Image">
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="bottom-icons">
    <a href="#"><img src="facebook.png" alt="Facebook"></a>
    <a href="#"><img src="instagram.png" alt="Instagram"></a>
    <a href="#"><img src="whatsapp.png" alt="WhatsApp"></a>
    <a href="#"><img src="tiktok.png" alt="Tiktok"></a>
</div>

<!-- Back to top button -->
<button class="back-to-top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">↑</button>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    const query = this.value;
    const box = document.getElementById("suggestions");

    if (query.length === 0) {
        box.classList.add("d-none");
        return;
    }

    fetch("suggest.php?query=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                box.classList.add("d-none");
                return;
            }

            box.innerHTML = "";
            data.forEach(item => {
                const div = document.createElement("div");
                div.className = "suggestion-item";
                div.innerText = item;
                div.onclick = () => {
                    document.getElementById("searchInput").value = item;
                    box.classList.add("d-none");
                };
                box.appendChild(div);
            });
            box.classList.remove("d-none");
        });
});
</script>

</body>
</html>
