<?php
	if(!is_logged()) {
		redirect(base_url("?m=user&a=login"));
	}
	global $error ;
	$error = array(
		'image' => '',
		'post_content' => ''
	);
	$avatar_url ="default.jpg"; 
	$target_dir = "uploads/".get_user_id()."/";
	if(is_submit("post")){
		if(isset($_FILES["fileToUpload"]["name"]) &&  basename($_FILES["fileToUpload"]["name"])!= ""){
			if(basename($_FILES["fileToUpload"]["name"])== ""){
				$error["image"]= "Hình ảnh không thể để trống.";
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
				if ($_FILES["fileToUpload"]["size"] > 5000000) {
					$error["image"]= "Tệp quá lớn.";
				}
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					$error["image"]= "Định dạng tệp không hỗ trợ.";
				}
			}
		}
		if (input_post('content')=="") 
			$error['post_content']= "Nội dung bài viết không thể để trống.";
		
		if ($error["image"]=="" && $error['post_content'] == ""){
			if(!is_dir($target_dir)) {
				mkdir($target_dir,  0700);
			}
			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
				$avatar_url = get_user_id().'/'.basename( $_FILES["fileToUpload"]["name"]);
			$data = array(
				'post_header'  => input_post('header'),
				'poster_id'  => get_user_id(),
				'post_content'  => input_post('content'),
				'avatar_url' => $avatar_url 
				);
			if(db_insert_post($data)){
				?>
					<script language="javascript">
						alert('Thêm bài viết thành công!');
						window.location = '<?php echo (base_url("?m=user&a=dashboard")); ?>';
					</script>
				<?php
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
<div class="row">
<form method="post" class="form new-post" action="<?php echo base_url("?m=user&a=dashboard"); ?>" enctype="multipart/form-data" >
	<h2 class="new-post"> BÀI VIẾT MỚI</h2>
	
	<div class = "col-sm-12 col-md-5">
		<div class="form-group">
			<img src="preview.jpg" id="img" class="preview" src="#" alt="your image" />
			<input type="file" class="preview" name="fileToUpload" onchange="readURL(this);">
			<h5><?php echo $error['image']; ?></h5>;
		</div>
	</div>
	<div class = "col-sm-12 col-md-7">
		<div class="form-group">
			<textarea class="form-control new-post" rows="2" name="header" placeholder="Tiêu đề"></textarea>
		</div>
		<div class="form-group">
			<textarea class="form-control new-post" rows="9" name="content" placeholder="Nội dung"></textarea>
			<h5><?php echo $error['post_content']; ?></h5>;
		</div>
	</div>
	<div class = "col-sm-12 col-md-7 ">
		<div class="form-group">
			<input type="hidden" name="request_name" value="post"/>
			<button type="submit"  class="btn btn-success">Đăng bài</button>
		</div>
	</div>
</form>
</div>
<?php
	$posts = db_all_post();
	$editURL = base_url("?m=user&a=edit");
	foreach ($posts as $post){
		echo 	'<div class="row">';
		echo 	'<div class="media">';
		echo 	'<div class = "col-sm-12 col-md-5">';
		echo	'<div class="media-left">';
		echo 	'<img src="uploads/'.$post["avatar_url"].'" class="media-object"/>';
		echo	"</div>";
		echo	"</div>";
		echo 	'<div class = "col-sm-12 col-md-7">';
		echo 		'<div class="media-body">';
		echo			'<h2 class="media-heading">'.$post["post_header"].'</h2>';
		echo 	'<h5> Người tạo: '.$post["username"].'. Ngày tạo: '.changeDateFormat($post["create_date"]).'</h5>';
		echo			"<p>".$post["post_content"]."</p>";
		if	($post["poster_id"]==get_user_id()){ 
		echo 	'<form method="post" action="'.$editURL.'">';
		echo 	'<div class="form-group">';
		echo 	'<input type="hidden" name="id" value="'.$post["id"].'"/>';
		echo 	'<input type="hidden" name="request_name" value="edit"/>';
		echo  	'<button type="submit"  class="btn btn-link">Sửa bài</button>';
		echo 	'</div>';
		echo 	'</form>';
		}
		echo  	"</div>";
		echo	"</div>";
		echo	"</div>";
		echo	"</div>";
	}
?>
</div>