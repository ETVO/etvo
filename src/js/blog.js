(jQuery)(
    function ($) {
        const postsDiv = $('#posts');
        const selectedCategory = $('#selectedCategory');
        const categories = $('.categories-filter');
        const filterCategory = $('input[name="filter-category"]');
        const postsPerPage = $('#postsPerPage');
        const page = $('#page');
        const searchMsg = $('#searchMsg');
        const noMoreMsg = $('#noMoreMsg');
        const notFoundMsg = $('#notFoundMsg');
        const loadMoreBtn = $('#loadMoreBtn');
        const loading = $('#loading');
        const clearFilters = $('#clearFilters');
        const searchInput = $('#searchInput');

        const UTIL_URL = '/manage/util/';


        $(filterCategory).click(function () {
            var newCategory = $(this).val();

            if (newCategory != selectedCategory.val()) {
                selectedCategory.val(newCategory);
                categories.find('.category').removeClass('selected');
                $(this).parent('.category').addClass('selected');
            }
            else {
                selectedCategory.val(0);
                $(this).parent('.category').removeClass('selected');
            }

            loadPosts(true);
        });

        
        $(clearFilters).click(function () {
            selectedCategory.val(0);
            categories.find('.category').removeClass('selected');
            searchInput.val('');
            loadPosts(true);
        });


        var typingTimer;
        $(searchInput).on('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                loadPosts(true);
            }, 300);
        })


        $(loadMoreBtn).click(() => { loadPosts(); });

        function loadPosts(empty = false) {
            var category = selectedCategory.val();
            var perPage = postsPerPage.val();
            var newPage = parseInt(page.val());
            var searchTerms = searchInput.val();

            if (empty)
                newPage = 1;
            else
                newPage++;

            loading.fadeIn(100);
            noMoreMsg.hide();
            notFoundMsg.hide();
            loadMoreBtn.show();
            
            searchMsg.hide();
            if(searchTerms) {
                searchMsg.find('b').text(searchTerms);
                searchMsg.show();
            }

            $.ajax({
                url: UTIL_URL + 'use_blog_util.php',
                data: {
                    function: 'fetch_posts_json',
                    args: JSON.stringify(
                        [
                            'embed',
                            category,
                            perPage,
                            newPage,
                            searchTerms
                        ]
                    )
                },
                dataType: "html",
                success: function (data) {
                    var response = JSON.parse(data);

                    loading.fadeOut(100);

                    if (empty) {
                        postsDiv.empty();
                    }

                    if (response[0]) {
                        // Success
                        page.val(newPage);
                        var posts = response[1];
                        showNewPosts(posts);
                    }
                    else {
                        // No posts
                        loadMoreBtn.hide();
                        if (empty)
                            notFoundMsg.fadeIn(200);
                        else
                            noMoreMsg.fadeIn(200);

                    }
                },
            });
        }

        function showNewPosts(posts) {
            posts.forEach(post => {
                const newPost = $('#templatePost').clone();
                newPost.removeAttr('id');

                var image = post.featured_image_data;

                var img = '';
                if (image) {
                    img = $('<img>', { src: image.src });
                }
                newPost.find('.image').append(img);

                var title = post.title.rendered;
                newPost.find('.title').html(title);

                var excerpt = post.excerpt.rendered;
                newPost.find('.excerpt').html(excerpt);

                var link = '/post/' + post.slug;
                newPost.find('a').attr('href', link);

                var date = post.date;
                var formatDate = new Date(date);

                newPost.find('.post-date').text(
                    formatDate.toLocaleDateString('en-uk', { year: "numeric", month: "2-digit", day: "2-digit" })
                );

                var categories = post.category;
                if (categories.length) {
                    categories = categories.map((v, i) => { return v.name });
                    console.log(categories);
                    newPost.find('.post-categories').html(categories.join(', '));
                }
                else {
                    newPost.find('.meta .separator').hide();
                }

                postsDiv.append(newPost);
                newPost.fadeIn(200);
            });
        }
    }
)