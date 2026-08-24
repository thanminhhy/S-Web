$(document).ready(function () {
    $(".add-to-cart").click(function (e) {
        e.preventDefault();

        // const addToCartBtn = e.target;
        const product = $(this).closest(".single-products");
        const productId = $(this).data("id");

        $.ajax({
            type: "POST",
            url: "/frontend/product/addCart",
            data: {
                productId: productId,
                quantity: 1,
            },
            success: function (data) {
                alert(data.message);
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    alert("Sản phẩm không tồn tại!");
                }
            },
        });
    });
});
