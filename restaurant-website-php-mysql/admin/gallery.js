$(document).ready(function () {
    // Xử lý thêm ảnh
    $('#add_image_bttn').click(function () {
        var image_name = $("#image_name_input").val(); // Lấy tên ảnh từ input
        var image = $("#add_gallery_imageUpload")[0].files[0]; // Lấy file ảnh từ input
        var formdata = new FormData();

        formdata.append("image_name", image_name);
        formdata.append("gallery_image", image);
        formdata.append("do", "Add");

        if ($.trim(image_name) === "") {
            alert("Vui lòng nhập tên ảnh!");
            return;
        }

        if (!image) {
            alert("Vui lòng chọn một tệp ảnh!");
            return;
        }

        $.ajax({
            url: "ajax_files/gallery_ajax.php", // Đường dẫn đến file xử lý PHP
            method: "POST",
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response); // Hiển thị phản hồi từ server
                location.reload(); // Tải lại trang sau khi thêm ảnh thành công
            },
            error: function (xhr, status, error) {
                alert("Đã xảy ra lỗi khi thêm ảnh!");
            }
        });
    });

    // Xử lý xóa ảnh
    $(document).on('click', '.delete_image_bttn', function () {
        var image_id = $(this).data('id'); // Lấy ID ảnh từ thuộc tính `data-id`
        var confirmation = confirm("Bạn có chắc chắn muốn xóa ảnh này không?");
        if (confirmation) {
            $.ajax({
                url: "ajax_files/gallery_ajax.php", // Đường dẫn đến file xử lý PHP
                method: "POST",
                data: {
                    do: "Delete",
                    image_id: image_id
                },
                success: function (response) {
                    alert(response); // Hiển thị phản hồi từ server
                    location.reload(); // Tải lại trang sau khi xóa ảnh thành công
                },
                error: function (xhr, status, error) {
                    alert("Đã xảy ra lỗi khi xóa ảnh!");
                }
            });
        }
    });
});