<?php
$pageTitle = "Import Data";
require_once "includes/db.php";

$imported = 0;
$duplicates = 0;
$errors = [];
$sourceUsed = "Local JSON Dataset";
$apiWarning = "";

// GBIF is a public biodiversity API. This endpoint searches plant occurrence
// records from Ethiopia and does not require an API key.
$apiUrl = "https://api.gbif.org/v1/occurrence/search?country=ET&kingdomKey=6&hasCoordinate=true&limit=25";
$jsonFile = __DIR__ . "/data/plants.json";
$requestedSource = $_GET["source"] ?? "json";

function getRemoteJson($url)
{
    // cURL is usually available in XAMPP. file_get_contents is the backup.
    if (function_exists("curl_init")) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
            return $response;
        }
        return false;
    }

    return @file_get_contents($url);
}

function loadPlantsFromLocalJson($jsonFile, &$errors)
{
    if (!file_exists($jsonFile)) {
        $errors[] = "The JSON data file was not found: data/plants.json";
        return [];
    }

    $jsonContent = file_get_contents($jsonFile);
    $plants = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $errors[] = "Invalid JSON format. Please check data/plants.json.";
        return [];
    }

    if (!is_array($plants) || empty($plants)) {
        $errors[] = "The plant dataset is empty.";
        return [];
    }

    return $plants;
}

function loadPlantsFromGbifApi($apiUrl)
{
    $jsonContent = getRemoteJson($apiUrl);

    if ($jsonContent === false) {
        return [];
    }

    $apiData = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($apiData["results"])) {
        return [];
    }

    $plants = [];
    $seenScientificNames = [];

    foreach ($apiData["results"] as $record) {
        $scientificName = trim($record["species"] ?? $record["scientificName"] ?? "");

        if ($scientificName === "" || isset($seenScientificNames[$scientificName])) {
            continue;
        }

        $seenScientificNames[$scientificName] = true;
        $commonName = trim($record["vernacularName"] ?? "");
        $displayName = $commonName !== "" ? $commonName : $scientificName;

        $plants[] = [
            "name" => $displayName,
            "scientific_name" => $scientificName,
            "region" => "Ethiopia",
            "category" => "Plant",
            "short_description" => "A plant species found in biodiversity occurrence records for Ethiopia.",
            "full_description" => "This record was fetched dynamically from the GBIF public occurrence API. GBIF provides biodiversity occurrence data contributed by institutions and observers around the world.",
            "uses" => "Uses should be researched and verified from trusted local or scientific sources.",
            "cultural_importance" => "Cultural importance should be added after checking trusted indigenous knowledge sources.",
            "growing_conditions" => "Growing conditions should be confirmed from botanical references for this species.",
            "image" => "placeholder.svg",
            "source_name" => "GBIF Occurrence API",
            "source_url" => $apiUrl,
            "featured" => count($plants) < 4 ? 1 : 0
        ];

        if (count($plants) >= 8) {
            break;
        }
    }

    return $plants;
}

if ($requestedSource === "api") {
    $plants = loadPlantsFromGbifApi($apiUrl);

    if (!empty($plants)) {
        $sourceUsed = "GBIF Occurrence API";
    } else {
        $apiWarning = "The API could not be reached or returned no usable records, so the importer used the local JSON fallback.";
        $plants = loadPlantsFromLocalJson($jsonFile, $errors);
    }
} else {
    $plants = loadPlantsFromLocalJson($jsonFile, $errors);
}

if (empty($errors) && !empty($plants)) {
        try {
            $checkSql = "SELECT id FROM plants WHERE scientific_name = :scientific_name OR name = :name LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);

            $insertSql = "INSERT INTO plants
                (name, scientific_name, region, category, short_description, full_description, uses, cultural_importance, growing_conditions, image, source_name, source_url, featured)
                VALUES
                (:name, :scientific_name, :region, :category, :short_description, :full_description, :uses, :cultural_importance, :growing_conditions, :image, :source_name, :source_url, :featured)";
            $insertStmt = $pdo->prepare($insertSql);

            foreach ($plants as $plant) {
                $name = trim($plant["name"] ?? "");
                $scientificName = trim($plant["scientific_name"] ?? "");

                if ($name === "") {
                    $errors[] = "A plant record was skipped because it has no name.";
                    continue;
                }

                $checkStmt->execute([
                    ":scientific_name" => $scientificName,
                    ":name" => $name
                ]);

                if ($checkStmt->fetch()) {
                    $duplicates++;
                    continue;
                }

                $insertStmt->execute([
                    ":name" => $name,
                    ":scientific_name" => $scientificName,
                    ":region" => $plant["region"] ?? "",
                    ":category" => $plant["category"] ?? "",
                    ":short_description" => $plant["short_description"] ?? "",
                    ":full_description" => $plant["full_description"] ?? "",
                    ":uses" => $plant["uses"] ?? "",
                    ":cultural_importance" => $plant["cultural_importance"] ?? "",
                    ":growing_conditions" => $plant["growing_conditions"] ?? "",
                    ":image" => $plant["image"] ?? "placeholder.svg",
                    ":source_name" => $plant["source_name"] ?? "Local JSON Dataset",
                    ":source_url" => $plant["source_url"] ?? "data/plants.json",
                    ":featured" => !empty($plant["featured"]) ? 1 : 0
                ]);

                $imported++;
            }
        } catch (PDOException $e) {
            $errors[] = "Import failed. Please confirm the database table exists by importing database/indigenous_plants.sql.";
        }
}

include "includes/header.php";
?>

<section class="page-header">
    <h1>Import Plant Data</h1>
    <p>This page can import from a live public API or from <strong>data/plants.json</strong>.</p>
</section>

<section class="section narrow">
    <div class="import-actions">
        <a class="button" href="import-plants.php?source=api">Import from GBIF API</a>
        <a class="button secondary" href="import-plants.php?source=json">Import from Local JSON</a>
    </div>

    <?php if ($apiWarning !== ""): ?>
        <div class="notice warning">
            <p><?php echo htmlspecialchars($apiWarning); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="notice warning">
            <h2>Import completed with issues</h2>
            <?php foreach ($errors as $message): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="notice success">
            <h2>Import completed.</h2>
            <p>Source used: <strong><?php echo htmlspecialchars($sourceUsed); ?></strong></p>
            <p>Plants imported: <strong><?php echo $imported; ?></strong></p>
            <p>Duplicates skipped: <strong><?php echo $duplicates; ?></strong></p>
        </div>
    <?php endif; ?>

    <div class="info-panel">
        <h2>How this works</h2>
        <p>PHP can request data from the GBIF API, decode it with <code>json_decode()</code>, reshape it for this project, check for existing plant names or scientific names, and insert only new records using prepared statements.</p>
        <p>If the live API is unavailable, the local JSON file still works as a mock external dataset for classroom demos.</p>
        <a class="button" href="plants.php">View Plants</a>
    </div>
</section>

<?php include "includes/footer.php"; ?>
