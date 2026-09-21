$(document).ready(function () {
    //Dùng foreach ở view để tạo ra nhiều form thì phải dùng event delegation để bắt element
    //Event delegation là phải bắt sự kiện từ element cha document
    //Những trường hợp nhiều form như thế này thì không nên bắt id thay vào đó nên bắt class
    //Chuẩn HTML thì mỗi ID là unique đại diện cho 1 element
    $(document).on("submit", ".ajax-update-user-form", function (e) {
        e.preventDefault();
        const currentForm = $(this);
        const actionUrl = currentForm.attr("action");

        const formData = new FormData(this);

        $.ajax({
            url: actionUrl,
            data: formData,
            type: "POST",
            processData: false, // BẮT BUỘC khi dùng FormData (chặn jQuery tự biến data thành query string)
            contentType: false, // BẮT BUỘC khi dùng FormData (để browser tự set Content-Type header)
            success: function (response) {
                const user = response.user;
                const currentUser = $("#user-row-" + user.id);
                console.log(currentUser.find(".user-name"));

                currentForm.closest(".modal").modal("hide");
                currentUser.find(".user-name").text(user.name);
                currentUser.find(".user-email").text(user.email);
                currentUser.find(".user-phone").text(user.phone);
                currentUser.find(".user-address").text(user.address);
                currentUser
                    .find(".user-country")
                    .text(user.country ? user.country.name : "");

                // Thông báo thành công mượt mà
                alert(response.message);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    console.log(errors);
                    //$.each in jquery giống object.entries trong js
                    $.each(errors, function (field, messages) {
                        currentForm
                            .find("[name='" + field + "']")
                            .addClass("is-invalid");

                        currentForm.find(".error-" + field).text(messages[0]);
                    });
                }
            },
        });
    });
});
