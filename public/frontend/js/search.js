$(document).ready(function () {
    let timer;
    //Handle when typing on search input
    $("#search-input").on("keyup", function () {
        let keyword = $(this).val();

        //Dùng clearTimeout để xóa setTimeout (debounce) đê không bị gửi quá nhiều request thừa khi người dùng gõ nhanh
        clearTimeout(timer);

        if (keyword.length < 2) {
            $("#search-result-box").hide();
            return;
        }

        timer = setTimeout(function () {
            $.ajax({
                url: "/api/live-search",
                type: "GET",
                // tự động chuyển response từ server ở dạng json sang array/object
                data: { keyword: keyword },
                success: function (response) {
                    let products = response.products;
                    let html = "";

                    if (products.length > 0) {
                        html += "<ul>";
                        products.forEach(function (product) {
                            html += `
                            <li>
                                <a href="javascript:void(0)" class="suggest-item">${product.name}</a>
                            </li>`;
                        });
                        html += "</ul>";

                        $("#search-result-box").html(html).show();
                    } else {
                        $("#search-result-box")
                            .html(
                                "<div class='no-result'>Không tìm thấy kết quả!</div>",
                            )
                            .show();
                    }
                },
            });
        }, 300);
    });

    // Handle to search specified item in search suggestions
    $(document).on("click", ".suggest-item", function () {
        let selectedItem = $(this).text();
        //1. Add suggest item to search input
        $("#search-input").val(selectedItem);

        //2. Hide dropdown list
        $("#search-result-box").hide();

        //3. Submit search input form
        $("#search-input").closest("form").submit();
    });

    //Function to send request without load page with ajax for filter search and pagination
    function fetchProducts(baseUrl, queryString = null) {
        $.ajax({
            url: baseUrl,
            type: "GET",
            data: queryString,
            beforeSend: function () {
                $("#ajax-product-list").css("opacity", "0.5");
            },
            success: function (response) {
                if (response.status === "success") {
                    $("#ajax-product-list")
                        .html(response.html)
                        .css("opacity", "1");

                    // Cập nhật thanh URL trình duyệt
                    let fullUrl = queryString
                        ? baseUrl + "?" + queryString
                        : baseUrl;
                    window.history.pushState({}, "", fullUrl);
                }
            },
        });
    }

    //To remove redundant filter field when we search filterly
    function getCleanQueryString(form) {
        //Dùng để chuyển form jquery sang DOM element khi cần
        // Ví dụ trường hợp truyền vào một $('#filter-form') sẽ bị lỗi nếu không đóng gói form trong jquery
        let formElement = $(form)[0];
        let params = new URLSearchParams(new FormData(formElement));

        //check keyword in filter form exist or not. If not run block to get the keyword from current URL
        if (!params.has("keyword")) {
            const currentUrlParams = new URLSearchParams(
                window.location.search,
            );

            const oldKeyword = currentUrlParams.get("keyword");

            //check whether oldKeyword exists or not and
            // ensure there's no empty string with space inside oldKeyword
            //ex: oldKeyword=" ". Trim will remove space.
            if (oldKeyword && oldKeyword.trim()) {
                params.set("keyword", oldKeyword.trim());
            }
        }
        //Loop over URLSearchParams and delete key that has empty values
        for (let [key, value] of Array.from(params.entries())) {
            if (!value.trim()) {
                params.delete(key);
            }
        }

        return params.toString();
    }
    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        const url = $(this).attr("action");
        const cleanData = getCleanQueryString(this);

        fetchProducts(url, cleanData);

        // $.ajax({
        //     url: $(this).attr("action"),
        //     type: $(this).attr("method") || "GET",
        //     data: $(this).serialize(),
        //     success: function (response) {
        //         $("#ajax-product-list").html(response.html);

        //         window.history.pushState(
        //             {},
        //             "",
        //             "?" + $("#filter-form").serialize(),
        //         );
        //     },
        // });
    });

    $(document).on("click", "#ajax-product-list .pagination a", function (e) {
        e.preventDefault();

        let pageUrl = $(this).attr("href");

        if (!pageUrl) return;

        const urlParts = pageUrl.split("?");
        const baseUrl = urlParts[0];
        const queryString = urlParts[1];

        fetchProducts(baseUrl, queryString);
    });
});
