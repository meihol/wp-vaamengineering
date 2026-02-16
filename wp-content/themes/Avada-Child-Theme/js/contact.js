jQuery(function($) {

    // Get the product category select element
    var productCategory = document.querySelector('#product_category');
    var terms = post.terms;

    // Loop through the terms and add options to the select element
    terms.forEach(function(term) {
        var option = document.createElement('option');
        option.text = term;
        option.value = term;
        productCategory.add(option);
    });

    // Calling the api on change the option for Product Category
    var productCategory = document.querySelector('#product_category');
    var product = document.querySelector('#product');

    // Add an event listener for the 'change' event
    productCategory.addEventListener('change', function() {
        // Get the selected term name
        var preloader = document.querySelector('#formspinner');
        preloader.classList.add('showloader');

        var termName = this.value;

        // Define the API endpoint URL
        var apiUrl = '/wp-json/wp/v2/posts?_embed&per_page=99&category=' + termName; // replace 'product_category' with your taxonomy slug

        // Fetch the posts using the API
        fetch(apiUrl)
            .then(function(response) {
                return response.json();
            })
            .then(function(posts) {
                // Do something with the retrieved posts
                console.log(posts);
                // Update the UI with the retrieved posts
                //updateUI(posts);
                product.innerHTML = '<option value="">Select a product</option>';
                posts.forEach(function(term) {
                    var option = document.createElement('option');
                    option.text = term.title.rendered;
                    option.value = term.title.rendered;
                    product.add(option);
                });
                preloader.classList.remove('showloader');
            })
            .catch(function(error) {
                console.error(error);
            });
    });
});