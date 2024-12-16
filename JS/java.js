// Wait for the document to be fully loaded
$(document).ready(function () {
    // Search functionality
    $('.search-button').on('click', function () {
        var searchQuery = $('.search-input').val().toLowerCase(); // Get search input and convert to lowercase

        // Loop through each product
        $('.product-box').each(function () {
            var productName = $(this).find('h3').text().toLowerCase(); // Get product name and convert to lowercase

            // If the product name contains the search query, show it, otherwise hide it
            if (productName.includes(searchQuery)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Optional: you can also add functionality to filter on keyup, so it searches as you type
    $('.search-input').on('keyup', function () {
        var searchQuery = $(this).val().toLowerCase(); // Get search input and convert to lowercase

        // Loop through each product
        $('.product-box').each(function () {
            var productName = $(this).find('h3').text().toLowerCase(); // Get product name and convert to lowercase

            // If the product name contains the search query, show it, otherwise hide it
            if (productName.includes(searchQuery)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
