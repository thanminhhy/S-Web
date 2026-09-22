$(document).ready(function () {
    $(document).on("click", ".btn-view-items", function () {
        const orderId = $(this).data("id");
        const detailUrl = $(this).data("url");
        // Reset bảng trong modal về trạng thái loading
        $("#modal-order-id").text(orderId);
        $("#order-items-list").html(
            '<tr><td colspan="6" class="text-center">Loading...</td></tr>',
        );

        // Mở Modal
        $("#orderItemsModal").modal("show");

        $.ajax({
            url: detailUrl,
            type: "GET",
            success: function (response) {
                let html = "";
                let items = response.items;

                if (items.length > 0) {
                    $.each(items, function (index, item) {
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}</td>
                            <td>${Number(item.price).toLocaleString()} VND</td>
                            <td>${item.quantity}</td>
                            <td>${(item.price * item.quantity).toLocaleString()} VND</td>
                        </tr>
                    `;
                    });
                } else {
                    html =
                        '<tr><td colspan="6" class="text-center">No items found.</td></tr>';
                }

                // Đổ HTML vào tbody trong Modal
                $("#order-items-list").html(html);
            },
            error: function () {
                $("#order-items-list").html(
                    '<tr><td colspan="6" class="text-center text-danger">Failed to load data.</td></tr>',
                );
            },
        });
    });
});
