<?php

	function getTitle()
	{
		global $pageTitle;
		if(isset($pageTitle))
			echo $pageTitle." | Vincent Restaurant - Your Restaurant";
		else
			echo "Vincent Restaurant | Your Restaurant";
	}

	/*
		Hàm này trả về số lượng mục trong một bảng nhất định
	*/

    function countItems($item,$table)
	{
		global $con;
		$stat_ = $con->prepare("SELECT COUNT($item) FROM $table");
		$stat_->execute();
		
		return $stat_->fetchColumn();
	}

    /*
	
	** Hàm Kiểm Tra Mục
	** Hàm để kiểm tra mục trong cơ sở dữ liệu [Hàm với tham số]
	** $select = mục cần chọn [Ví dụ: user, item, category]
	** $from = bảng để chọn từ [Ví dụ: users, items, categories]
	** $value = Giá trị của mục chọn [Ví dụ: Ossama, Box, Electronics]

	*/
	function checkItem($select, $from, $value)
	{
		global $con;
		$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
		$statment->execute(array($value));
		$count = $statment->rowCount();
		
		return $count;
	}


  	/*
    	==============================================
    	HÀM KIỂM TRA ĐẦU VÀO, ĐƯỢC SỬ DỤNG ĐỂ LÀM SẠCH ĐẦU VÀO CỦA NGƯỜI DÙNG
    	VÀ LOẠI BỎ CÁC KÝ TỰ ĐÁNG NGỜ và Loại Bỏ Khoảng Trắng Thừa
    	==============================================
	
	*/

  	function test_input($data) 
  	{
      	$data = trim($data);
      	$data = stripslashes($data);
      	$data = htmlspecialchars($data);
      	return $data;
  	}









?>

