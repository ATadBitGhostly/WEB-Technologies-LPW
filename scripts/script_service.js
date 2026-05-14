const cards = Array.from(document.querySelectorAll("#services-grid .col-lg-3"));
const searchInput = document.getElementById("search-input");
const categorySelect = document.getElementById("category-select");

function applyFilters() {
    const keyword = searchInput.value.toLowerCase();
    const category = categorySelect.value;

    cards.forEach(card => {
        const matchesCategory = category === "all" || card.dataset.category === category;
        const matchesKeyword = card.dataset.keywords.includes(keyword) || card.innerText.toLowerCase().includes(keyword);
        card.classList.toggle("hidden", !(matchesCategory && matchesKeyword));
    });
}

searchInput.addEventListener("input", applyFilters);
categorySelect.addEventListener("change", applyFilters);


