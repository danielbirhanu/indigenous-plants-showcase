<?php
$pageTitle = "About";
include "includes/header.php";
?>

<section class="page-header">
    <h1>About This Project</h1>
    <p>A simple final project showing how plant knowledge can be organized, searched, and imported from a data source.</p>
</section>

<section class="section text-page">
    <h2>Purpose</h2>
    <p>The Indigenous Plants Showcase helps users browse plant names, regions, uses, growing conditions, and cultural importance in one clean website.</p>

    <h2>Why Indigenous Plant Knowledge Matters</h2>
    <p>Indigenous plant knowledge supports food security, traditional medicine, conservation, and respect for local environments. Recording this information carefully helps students understand both web development and the importance of responsible data sharing.</p>

    <h2>Data Source</h2>
    <p>The project imports plant records from <strong>data/plants.json</strong>. This file acts like an external API-style dataset, so the SQL file only creates the database and table.</p>

    <h2>Source Credit</h2>
    <p>Plant information should always be checked, improved, and credited. In a real project, the dataset should include trusted source names and URLs for each record.</p>
</section>

<?php include "includes/footer.php"; ?>
