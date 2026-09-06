$(document).ready(function () {
    //Add cart
    $(".add-to-cart").on("click", function (e) {
        e.preventDefault();

        // const addToCartBtn = e.target;
        const productId = $(this).data("id");

        $.ajax({
            type: "POST",
            url: "/frontend/cart/addProduct",
            data: {
                productId: productId,
                quantity: 1,
            },
            success: function (data) {
                $("#cart-count").text(data.totalQuantity);
                alert(data.message);
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    alert("Sản phẩm không tồn tại!");
                }
            },
        });
    });

    //Update cart quantity
    $(".cart_quantity_up").on("click", function (e) {
        e.preventDefault();

        const item = $(this).closest("tr");
        const productId = $(this).data("product-id");
        const currentQty = parseInt(item.find(".cart_quantity_input").val());
        const newQty = currentQty + 1;

        updateCartQuantity(productId, newQty, item);
    });

    $(".cart_quantity_down").on("click", function (e) {
        e.preventDefault();

        const item = $(this).closest("tr");
        const productId = $(this).data("product-id");
        const currentQty = parseInt(item.find(".cart_quantity_input").val());
        const newQty = currentQty - 1;

        updateCartQuantity(productId, newQty, item);
    });

    $(".cart_quantity_input").on("change", function (e) {
        e.preventDefault();

        const item = $(this).closest("tr");
        const productId = $(this).data("product-id");
        const newQty = parseInt($(this).val());
        console.log(item, productId, newQty);

        updateCartQuantity(productId, newQty, item);
    });

    function updateCartQuantity(productId, newQty, currentItem) {
        $.ajax({
            type: "POST",
            url: "/frontend/cart/updateQuantity",
            data: {
                productId,
                newQty,
            },
            success: function (data) {
                currentItem.find(".cart_quantity_input").val(newQty);
                currentItem
                    .find(".item_total_price")
                    .text(`${data.itemSubTotal} VND`);
                $("#cart_total_price").text(`${data.cartSubTotal} VND`);
                $("#cart_eco_tax").text(`${data.ecoTax} VND`);
                $("#cart_shipping_cost").text(`${data.shippingCost} VND`);
                $("#grand_total_cart").text(`${data.grandTotal} VND`);
                alert(data.message);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    alert("Số lượng item phải có ít nhất 1 sản phẩm");
                }
            },
        });
    }

    //Process Order
    $("#order-btn").on("click", function (e) {
        const orderUrl = $(this).data("url");

        $.ajax({
            type: "POST",
            url: orderUrl,
            success: function (response) {
                alert(response.message);
            },
            error: function (xhr) {},
        });
    });
});
