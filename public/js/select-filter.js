// Pour les catégories
document.getElementById('category-select').addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});

// Pour les filtres "sort"
document.getElementById('sort-select').addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});

// Pour le nombre de produits à afficher
document.getElementById('max-results-select').addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});

// Pour le prix minimum
document.getElementById('price-min-select').addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});

// Pour le prix maximum
document.getElementById('price-max-select').addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});