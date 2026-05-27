<?php
$pageTitle = "Plant Details";
require_once "includes/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$plant = null;

if (!$id) {
    $error = "Missing or invalid plant ID.";
} else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM plants WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $plant = $stmt->fetch();

        if (!$plant) {
            $error = "Plant not found.";
        } else {
            $pageTitle = $plant["name"];
        }
    } catch (PDOException $e) {
        $error = "Plant details could not be loaded.";
    }
}

include "includes/header.php";
?>

<section class="section">
    <?php if (!empty($error)): ?>
        <div class="notice warning">
            <?php echo htmlspecialchars($error); ?>
            <p><a href="plants.php">Return to Plants</a></p>
        </div>
    <?php else: ?>
        <article class="detail-layout">
            <div>
                <img class="detail-image" src="assets/images/<?php echo htmlspecialchars($plant["image"] ?: "placeholder.svg"); ?>" alt="<?php echo htmlspecialchars($plant["name"]); ?>">
            </div>
            <div class="detail-content">
                <p class="tag"><?php echo htmlspecialchars($plant["category"]); ?></p>
                <h1><?php echo htmlspecialchars($plant["name"]); ?></h1>
                <p class="scientific"><?php echo htmlspecialchars($plant["scientific_name"]); ?></p>
                <p><strong>Region found:</strong> <?php echo htmlspecialchars($plant["region"]); ?></p>
                <p><?php echo nl2br(htmlspecialchars($plant["full_description"])); ?></p>

                <h2>Uses</h2>
                <p><?php echo nl2br(htmlspecialchars($plant["uses"])); ?></p>

                <h2>Cultural Importance</h2>
                <p><?php echo nl2br(htmlspecialchars($plant["cultural_importance"])); ?></p>

                <h2>Growing Conditions</h2>
                <p><?php echo nl2br(htmlspecialchars($plant["growing_conditions"])); ?></p>

                <div class="source-box">
                    <h2>Data Source</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($plant["source_name"] ?: "Not provided"); ?></p>
                    <p><strong>URL/File:</strong> <?php echo htmlspecialchars($plant["source_url"] ?: "Not provided"); ?></p>
                </div>

                <a class="button secondary" href="plants.php">Back to Plants</a>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
