$(document).ready(function () {
    $(document).on("change", "#images", function (e) {
        const previewContainer = $("#preview-container");
        const files = Array.from(this.files);
        // const file = e.target.files[0];
        // if (!file) return;

        //1. Reset khung preview khi user thêm ảnh mới vào khung
        previewContainer.empty();
        console.log(files.length);

        //2. Kiểm tra số lượng images có lớn hơn 3 không
        if (files.length > 3) {
            alert("Bạn chỉ được chọn tối đa 3 hình ảnh!");
            $(this).val("");
            // $("#preview").attr("src", "").show();
            return;
        }

        files.forEach((file) => {
            if (file.type.match("image.*")) {
                const imgUrl = URL.createObjectURL(file);
                const imgHtml = `
                    <div class="preview-box">
                        <img src="${imgUrl}" alt="preview">
                    </div>`;
                previewContainer.append(imgHtml);
            }
        });
        // $("#preview").attr("src", URL.createObjectURL(file)).show();
        // preview.src = URL.createObjectURL(file);
        // preview.style.display = "block";
    });

    function handleStatusChange() {
        let selectedStatus = $("#product-status").val();
        if (selectedStatus === "sale") {
            $(".sale-group").slideDown(200);
        } else {
            $(".sale-group").slideUp(200);
            $(".sale-group input[name='sale']").val(0);
        }
    }
    $(document).on("change", "#product-status", function () {
        handleStatusChange();
    });

    handleStatusChange();
});
