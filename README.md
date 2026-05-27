# Indigenous Plants Showcase

A beginner-friendly full-stack final project built with HTML, CSS, JavaScript, PHP, PDO, and MySQL.

Plant records are not inserted manually in the SQL file. Instead, `import-plants.php` can import from the public GBIF API or read the mock external dataset in `data/plants.json`, then save records into MySQL while skipping duplicates.

## Local URLs

- Project: `http://localhost/indigenous-plants-showcase/`
- Import local JSON data: `http://localhost/indigenous-plants-showcase/import-plants.php?source=json`
- Import API data: `http://localhost/indigenous-plants-showcase/import-plants.php?source=api`

## Setup

1. Place this project folder in `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin: `http://localhost/phpmyadmin`
4. Import `database/indigenous_plants.sql`.
5. Open `http://localhost/indigenous-plants-showcase/`.
6. Run `http://localhost/indigenous-plants-showcase/import-plants.php?source=json` or `http://localhost/indigenous-plants-showcase/import-plants.php?source=api`.
7. Visit the Plants page to confirm the records are displayed.
