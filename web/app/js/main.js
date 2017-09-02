$(document).ready(function() {

    var searchBtn = $('.search-form button');
    var searchInput = $(".search-form input[type='text']");

    toggleSearchBtn();

    $(document).on('input', searchInput,  function() {
        toggleSearchBtn();
    });

    /* highlight search text in content */
    if (searchText) {
        var text = '(' + searchText + ')';
        var re = new RegExp(text, "ig");

        $('p.tweet-content').each(function () {
            var html = $(this).html();
            var newHtml = html.replace(re, "<span class='search-text'>$1</span>");
            $(this).html(newHtml);
        });
    }

    /* enable or disable search button */
    function toggleSearchBtn() {
        if (searchInput.val().length > 2) {
            searchBtn.removeAttr('disabled');
        } else {
            searchBtn.attr('disabled', 'disabled');
        }
    }
});
