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
                data: { keyword: keyword },
                success: function (response) {},
            });
        }, 300);
    });
});
