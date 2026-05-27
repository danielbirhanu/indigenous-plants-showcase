// Mobile navigation and instant plant filtering.
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("open");
        });
    }

    const searchInput = document.getElementById("plantSearch");
    const regionFilter = document.getElementById("regionFilter");
    const categoryFilter = document.getElementById("categoryFilter");
    const cards = document.querySelectorAll(".filter-card");
    const visibleCount = document.getElementById("visibleCount");

    function filterPlants() {
        const searchText = searchInput ? searchInput.value.toLowerCase().trim() : "";
        const selectedRegion = regionFilter ? regionFilter.value : "";
        const selectedCategory = categoryFilter ? categoryFilter.value : "";
        let shown = 0;

        cards.forEach(function (card) {
            const name = card.dataset.name || "";
            const scientific = card.dataset.scientific || "";
            const region = card.dataset.region || "";
            const category = card.dataset.category || "";

            const matchesSearch = name.includes(searchText) || scientific.includes(searchText);
            const matchesRegion = selectedRegion === "" || region === selectedRegion;
            const matchesCategory = selectedCategory === "" || category === selectedCategory;
            const shouldShow = matchesSearch && matchesRegion && matchesCategory;

            card.style.display = shouldShow ? "" : "none";
            if (shouldShow) {
                shown++;
            }
        });

        if (visibleCount) {
            visibleCount.textContent = shown;
        }
    }

    if (searchInput) {
        searchInput.addEventListener("input", filterPlants);
    }
    if (regionFilter) {
        regionFilter.addEventListener("change", filterPlants);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener("change", filterPlants);
    }
});
