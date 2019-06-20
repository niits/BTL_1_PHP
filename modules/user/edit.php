<?php
	if(!is_logged()){
		redirect(base_url("?m=user&a=login"));
	}
	set_edit_post(input_post('id'));
	$post = db_get_post_by_id(get_edit_post());
	$avatar_url = $post['avatar_url'];
	global $error ;
	$error = array(
		'image' => '',
		'post' => '',
		'perform'=> ''
	);	
	$target_dir = "uploads/".get_user_id()."/";
	if(is_submit("back")){
		redirect(base_url("?m=user&a=dashboard"));
	}
	else if(is_submit("delete")){
		if (delete_post(get_edit_post())){
			?> 
				<script language="javascript">
				   alert('Xóa bài viết thành công!');
					window.location = '<?php echo (base_url("?m=user&a=dashboard")); ?>';
				</script>
			<?php
		die();
	   }
	   else{
		$error["perform"]= "Quá trình thực hiện yêu cầu bị lỗi.";
	   }
	}
	else if(is_submit("re-post")){
		if(isset($_FILES["fileToUpload"]["name"])){
			if(basename($_FILES["fileToUpload"]["name"])== ""){
				$error["image"]= "Ảnh đại diện của bài viết sẽ không thay đổi.";
			}
			else{
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check == false){
					$error["image"]= "Tập tin không phải hình ảnh.";
				}
				if (file_exists($target_file)) {
					$error["image"]= "Tên tệp đã tồn tại vui lòng đặt tên khác cho file ảnh.";
				}
				if ($_FILES["fileToUpload"]["size"] > 500000) {
					$error["image"]= "Tệp quá lớn.";
				}
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
					$error["image"]= "Định dạng tệp không hỗ trợ.";
				}
			}
		}
		if(	((input_post('content')=="") || (input_post('content')==$post['post_content'])) && ((input_post('header')=="") || (input_post('header')==$post['post_header']))) {
			$error['post'] = "Nội dung bài viết trống hoặc không được thay đổi.";
		}
		if ($error['post']=="" ||  $error["image"]==""){
			if(!is_dir($target_dir)) {
				mkdir($target_dir,  0700);
			}
			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
				$avatar_url = get_user_id().'/'.basename( $_FILES["fileToUpload"]["name"]);
			}
			$data = array(
				'post_header'  	=> input_post('header'),
				'id'  			=> get_edit_post(),
				'post_content'  => input_post('content'),
				'avatar_url' 	=> $avatar_url
				);
			if (edit_post($data)){
				 ?> 
					 <script language="javascript">
						alert('Sửa bài viết thành công!');
						 window.location = '<?php echo (base_url("?m=user&a=dashboard")); ?>';
					 </script>
				 <?php
			 die();
			}
			else{
				$error["perform"]= "Quá trình thực hiện yêu cầu bị lỗi.";
			   }
		}
	}
?>
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>                        
			</button>
		<a class="navbar-brand" href="<?php echo (base_url("?m=user&a=dashboard"));?>">Ti tỉ thứ linh tinh</a>
		</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a><?php echo get_username()?></a></li>
					<li><a href="<?php echo (base_url("?m=user&a=logout")); ?>"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
				</ul>
		</div>
	</div>
</nav>
<div class= "container">
	<div class="row form-container">
		<form method="post" class="form new-post" action="<?php echo base_url("?m=user&a=edit"); ?>" enctype="multipart/form-data" >
			<h2 class="new-post"> SỬA BÀI VIẾT</h2>
			
			<div class = "col-sm-12 col-md-4">
				<div class="form-group">
					<img src="<?php echo "uploads/".$post["avatar_url"]?>" id="img" class="media-object" src="#" alt="your image" />
					<input type="file" name="fileToUpload" onchange="readURL(this);">
					<h5><?php echo $error["image"] ?></h5>;
				</div>
			</div>
			<div class = "col-sm-12 col-md-8">
				<div class="form-group">
					<textarea class="form-control new-post" rows="2" name="header" placeholder="Tiêu đề"><?php echo $post["post_header"]; ?></textarea>
				</div>
				<div class="form-group">
					<textarea class="form-control new-post" rows="5" name="content" placeholder="Nội dung"><?php echo $post["post_content"]; ?></textarea>
					<h5><?php echo $error['post'] ?></h5>;
				</div>
			</div>
				<div class = "col-sm-12 col-md-8 text-right">
				<div class="form-group">
					<input type="hidden" name="request_name" value="re-post"/>
					<input type="hidden" name="id" value="<?php echo get_edit_post();?>"/>
					<button type="submit"  class="btn btn-success">Sửa bài</button>
				</div>
			</div>
		</form>
	</div>
	<div class="row form-container">
		<div class="col-md-6 text-left">
		<form method="post"  action="<?php echo base_url("?m=user&a=edit"); ?>">
				<div class="form-group">
					<input type="hidden" name="request_name" value="delete"/>
					<input type="hidden" name="id" value="<?php echo get_edit_post();?>"/>
					<button type="submit"  class="btn btn-primary">Xóa bài</button>
				</div>
		</form>
		</div>
		<div class="col-md-6 text-right">
		<form method="post"  action="<?php echo base_url("?m=user&a=edit"); ?>">
				<div class="form-group">
					<input type="hidden" name="request_name" value="back"/>
					<input type="hidden" name="id" value="<?php echo get_edit_post();?>"/>
					<button type="submit"  class="btn btn-primary">Quay lại</button>
			</div>
		</form>
		</div>
	</div>
</div>