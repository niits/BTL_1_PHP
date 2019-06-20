<?php
	global $error;
	$data = array(
		'username'  => input_post('username'),
		'password'  => input_post('password')
	);
	if(input_post('password') != input_post('re-password')) {
		$error["password"] = "Mật khẩu bạn nhập không khớp.";
	}
	if (db_user_validate($data["username"])) {
		$error["username"] = "Tên đăng nhập đã tồn tại.";
	}
	if (is_submit('signup')&& !$error){
			if (db_insert_user($data)){
				$user = db_user_get_by_username($data["username"]);
				mkdir("uploads/".$user["id"]);
				?>
				<script language="javascript">
					alert('Thêm người dùng thành công!');
					window.location = '<?php echo (base_url("?m=user&a=login")); ?>';
				</script>
				<?php
				die();
		}
	}
?>

		<div class="container text-center">
			<form method="post" class="form login-form" action="<?php echo base_url("?m=user&a=signup"); ?>" ?>
				<h2>ĐĂNG KÝ</h2>
				<div class="form-group">
					<input type="text" class="form-control" name="username" placeholder="Tên đăng nhập" required="true">
					<?php show_error($error, "username"); ?>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Mật khẩu" required="true">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="re-password" placeholder="Nhập lại mật khẩu"
						required="true">
					<?php show_error($error, "password"); ?>
				</div>
				<div class="form-group">
					<input type="hidden" name="request_name" value="signup" />
					<button type="submit" class="btn btn-default text-center">Đăng ký</button>
				</div>
				<div class="form-group">
					<a href="<?php echo base_url("?m=user&a=login") ?>" class="btn btn-link">Đã có tài khoản?</a>
				</div>
			</form>
		</div>