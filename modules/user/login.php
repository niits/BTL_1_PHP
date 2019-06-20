<?php
	global $error;
	$errors = array();
	if(is_logged()) {
		redirect(base_url("?m=user&a=dashboard"));
	}
	else if(is_submit("login")){
		$username = input_post("username");
		$password = input_post("password");
		if (!$error){
			$user = db_user_get_by_username($username);
			if ( empty($user)){
				if(!empty($username))	{
					$error["username"] = "Tên đăng nhập không đúng.";
				}
				else {
					$error["username"] = "Bạn chưa nhập tên đăng nhập";
				}
			}
			else if ($user["password"] != $password){
					$error["password"] = "Mật khẩu bạn nhập không đúng hoặc trống.";
			}
			if (!$error){
				set_logged(array("username" => $user["username"], "id" => $user["id"]));
				redirect(base_url("?m=user&a=dashboard"));
			}
		}
	}
?>
 
		<div class= "container text-center">
				<form method="post" class="form login-form" action="<?php echo base_url("?m=user&a=login"); ?>" ?>
					<h2>ĐĂNG NHẬP</h2>
					<div class="form-group">
						<input type="text" class="form-control" name="username" placeholder="Tên đăng nhập">
						<?php show_error($error, "username" ); ?>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Mật khẩu">
						<?php show_error($error, "password"); ?>
					</div>
					<div class="form-group">
						<input type="hidden" name="request_name" value="login"/>
						<button type="submit"  class="btn btn-default text-center">Đăng nhập</button>
					</div>
					<div class="form-group">
						<a href= "<?php echo base_url("?m=user&a=signup") ?>" class="btn btn-link">Tạo tài khoản</button>
					</div>
				</form>
		</div>