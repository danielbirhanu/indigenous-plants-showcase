<?php
$pageTitle = "Home";
require_once "includes/db.php";

$featuredPlants = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM plants WHERE featured = 1 ORDER BY name LIMIT 4");
    $stmt->execute();
    $featuredPlants = $stmt->fetchAll();
} catch (PDOException $e) {
    $featuredError = "Featured plants could not be loaded. Please import the database and plant data first.";
}

include "includes/header.php";
?>

<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Local knowledge, living plants</p>
        <h1>Indigenous Plants Showcase</h1>
        <p>Browse useful indigenous plants, learn where they grow, and see how plant data can be imported from a JSON dataset into MySQL.</p>
        <div class="hero-actions">
            <a class="button" href="plants.php">Browse Plants</a>
            <a class="button secondary" href="import-plants.php">Import Data</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <h2>Featured Plants</h2>
        <p>These records come from the MySQL database after running the import script.</p>
    </div>

    <?php if (!empty($featuredError)): ?>
        <div class="notice warning"><?php echo htmlspecialchars($featuredError); ?></div>
    <?php elseif (empty($featuredPlants)): ?>
        <div class="notice">
            No featured plants found yet. Run the import script to load records from <strong>data/plants.json</strong>.
        </div>
    <?php else: ?>
        <div class="plant-grid">
            <?php foreach ($featuredPlants as $plant): ?>
                <article class="plant-card">
                    <img src="assets/images/<?php echo htmlspecialchars($plant["image"] ?: "placeholder.svg"); ?>" alt="<?php echo htmlspecialchars($plant["name"]); ?>">
                    <div class="card-body">
                        <p class="tag"><?php echo htmlspecialchars($plant["category"]); ?></p>
                        <h3><?php echo htmlspecialchars($plant["name"]); ?></h3>
                        <p class="scientific"><?php echo htmlspecialchars($plant["scientific_name"]); ?></p>
                        <p><?php echo htmlspecialchars($plant["short_description"]); ?></p>
                        <a class="text-link" href="plant-details.php?id=<?php echo (int) $plant["id"]; ?>">View Details</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section source-band">
    <h2>Source Attribution</h2>
    <p>This project uses a local JSON file as a mock external data source. The import script reads, validates, and inserts plant records into MySQL using prepared statements.</p>
</section>

<?php include "includes/footer.php"; ?>
