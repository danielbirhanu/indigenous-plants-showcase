<?php
$pageTitle = "Plants";
require_once "includes/db.php";

$search = trim($_GET["search"] ?? "");
$region = trim($_GET["region"] ?? "");
$category = trim($_GET["category"] ?? "");
$plants = [];
$regions = [];
$categories = [];

try {
    $regions = $pdo->query("SELECT DISTINCT region FROM plants WHERE region IS NOT NULL AND region != '' ORDER BY region")->fetchAll(PDO::FETCH_COLUMN);
    $categories = $pdo->query("SELECT DISTINCT category FROM plants WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

    $sql = "SELECT * FROM plants WHERE 1=1";
    $params = [];

    if ($search !== "") {
        $sql .= " AND (name LIKE :search OR scientific_name LIKE :search)";
        $params[":search"] = "%" . $search . "%";
    }
    if ($region !== "") {
        $sql .= " AND region = :region";
        $params[":region"] = $region;
    }
    if ($category !== "") {
        $sql .= " AND category = :category";
        $params[":category"] = $category;
    }

    $sql .= " ORDER BY name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $plants = $stmt->fetchAll();
} catch (PDOException $e) {
    $pageError = "Plant records could not be loaded. Please import the SQL file and run the import script.";
}

include "includes/header.php";
?>

<section class="page-header">
    <h1>Plant Gallery</h1>
    <p>Search by common name, scientific name, region, or plant type.</p>
</section>

<section class="section">
    <?php if (!empty($pageError)): ?>
        <div class="notice warning"><?php echo htmlspecialchars($pageError); ?></div>
    <?php else: ?>
        <form class="filters" method="get" action="plants.php">
            <label>
                Search
                <input type="search" id="plantSearch" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Plant name or scientific name">
            </label>
            <label>
                Region
                <select id="regionFilter" name="region">
                    <option value="">All regions</option>
                    <?php foreach ($regions as $item): ?>
                        <option value="<?php echo htmlspecialchars($item); ?>" <?php echo $region === $item ? "selected" : ""; ?>><?php echo htmlspecialchars($item); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                Category
                <select id="categoryFilter" name="category">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $item): ?>
                        <option value="<?php echo htmlspecialchars($item); ?>" <?php echo $category === $item ? "selected" : ""; ?>><?php echo htmlspecialchars($item); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="button small" type="submit">Apply</button>
            <a class="button small secondary" href="plants.php">Reset</a>
        </form>

        <p class="result-count"><span id="visibleCount"><?php echo count($plants); ?></span> plant(s) shown</p>

        <?php if (empty($plants)): ?>
            <div class="notice">
                No plants found. Run <a href="import-plants.php">Import Data</a> if the database is empty.
            </div>
        <?php else: ?>
            <div class="plant-grid" id="plantGrid">
                <?php foreach ($plants as $plant): ?>
                    <article class="plant-card filter-card"
                        data-name="<?php echo htmlspecialchars(strtolower($plant["name"])); ?>"
                        data-scientific="<?php echo htmlspecialchars(strtolower($plant["scientific_name"])); ?>"
                        data-region="<?php echo htmlspecialchars($plant["region"]); ?>"
                        data-category="<?php echo htmlspecialchars($plant["category"]); ?>">
                        <img src="assets/images/<?php echo htmlspecialchars($plant["image"] ?: "placeholder.svg"); ?>" alt="<?php echo htmlspecialchars($plant["name"]); ?>">
                        <div class="card-body">
                            <p class="tag"><?php echo htmlspecialchars($plant["category"]); ?></p>
                            <h3><?php echo htmlspecialchars($plant["name"]); ?></h3>
                            <p class="scientific"><?php echo htmlspecialchars($plant["scientific_name"]); ?></p>
                            <p><strong>Region:</strong> <?php echo htmlspecialchars($plant["region"]); ?></p>
                            <p><?php echo htmlspecialchars($plant["short_description"]); ?></p>
                            <?php if (!empty($plant["source_name"])): ?>
                                <p class="source">Source: <?php echo htmlspecialchars($plant["source_name"]); ?></p>
                            <?php endif; ?>
                            <a class="text-link" href="plant-details.php?id=<?php echo (int) $plant["id"]; ?>">View Details</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
