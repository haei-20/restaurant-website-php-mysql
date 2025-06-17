<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	
	if(isset($_POST['do']) && $_POST['do'] == "Add")
	{
        $category_name = test_input($_POST['category_name']);

        $checkItem = checkItem("category_name","menu_categories",$category_name);

        if($checkItem != 0)
        {
            $data['alert'] = "Warning";
            $data['message'] = "This category name already exists!";
            echo json_encode($data);
            exit();
        }
        elseif($checkItem == 0)
        {
        	//Insert into the database
            $stmt = $con->prepare("insert into menu_categories(category_name) values(?) ");
            $stmt->execute(array($category_name));

            $data['alert'] = "Success";
            $data['message'] = "The new category has been inserted successfully !";
            echo json_encode($data);
            exit();
        }
            
	}

	if(isset($_POST['do']) && $_POST['do'] == "Delete")
	{
		$category_id = $_POST['category_id'];

        // Kiểm tra xem danh mục có tồn tại không
        $check = $con->prepare("SELECT COUNT(*) FROM menu_categories WHERE category_id = ?");
        $check->execute(array($category_id));
        if($check->fetchColumn() == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy danh mục này!']);
            exit();
        }

        // Kiểm tra xem có menu nào thuộc danh mục này không
        $check = $con->prepare("SELECT COUNT(*) FROM menus WHERE category_id = ?");
        $check->execute(array($category_id));
        if($check->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa danh mục này vì còn món ăn thuộc danh mục!']);
            exit();
        }

        // Xóa danh mục
        $stmt = $con->prepare("DELETE FROM menu_categories WHERE category_id = ?");
        if($stmt->execute(array($category_id))) {
            echo json_encode(['status' => 'success', 'message' => 'Danh mục đã được xóa thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi xóa danh mục!']);
        }
	}
	
?>