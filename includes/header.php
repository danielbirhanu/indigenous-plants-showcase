<?php
// Shared page header and navigation.
$currentPage = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . " | " : ""; ?>Indigenous Plants Showcase</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a class="brand" href="index.php">Indigenous Plants Showcase</a>
            <button class="menu-toggle" type="button" aria-label="Toggle menu">Menu</button>
            <ul class="nav-links">
                <li><a class="<?php echo $currentPage === "index.php" ? "active" : ""; ?>" href="index.php">Home</a></li>
                <li><a class="<?php echo $currentPage === "plants.php" ? "active" : ""; ?>" href="plants.php">Plants</a></li>
                <li><a class="<?php echo $currentPage === "import-plants.php" ? "active" : ""; ?>" href="import-plants.php">Import Data</a></li>
                <li><a class="<?php echo $currentPage === "about.php" ? "active" : ""; ?>" href="about.php">About</a></li>
                <li><a class="<?php echo $currentPage === "contact.php" ? "active" : ""; ?>" href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
