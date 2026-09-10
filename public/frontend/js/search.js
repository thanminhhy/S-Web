$(document).ready(function () {
    let timer;
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

    $(document).on("click", ".suggest-item", function () {
        let selectedItem = $(this).text();
        //1. Add suggest item to search input
        $("#search-input").val(selectedItem);

        //2. Hide dropdown list
        $("#search-result-box").hide();

        //3. Submit search input form
        $("#search-input").closest("form").submit();
    });

    function fetchProducts(url, data = null) {
        $.ajax({
            url: url,
            type: "GET",
            data: data,
            beforeSend: function () {
                $("#ajax-product-list").css("opacity", "0.5");
            },
            success: function (response) {
                if (response.status === "success") {
                    $("#ajax-product-list")
                        .html(response.html)
                        .css("opacity", "1");

                    // Cập nhật thanh URL trình duyệt
                    let fullUrl = data ? url + "?" + data : url;
                    window.history.pushState({}, "", fullUrl);
                }
            },
        });
    }
    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        const url = $(this).attr("action");
        const data = $(this).serialize();
        fetchProducts(url, data);
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

        fetchProducts(pageUrl);
    });
});
