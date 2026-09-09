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
});
