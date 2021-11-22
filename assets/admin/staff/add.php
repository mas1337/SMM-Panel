<?php
session_start();
require("../../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Admin") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_name = $_POST['name'];
			$post_fbid = $_POST['fbid'];
			$post_level = $_POST['level'];

			$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE name = '$post_name'");
			$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
			if (empty($post_name) || empty($post_fbid)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
			} else if ($post_level != "Admin" AND $post_level != "Reseller") {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Input tidak sesuai.";
			} else if (mysqli_num_rows($checkdb_staff) > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Staff $post_name sudah terdaftar dalam database.";
			} else {
				$insert_staff = mysqli_query($db, "INSERT INTO staff (name, fbid, level) VALUES ('$post_name', '$post_fbid', '$post_level')");
				if ($insert_staff == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Success:</b> Staff berhasil ditambahkan.<br /><b>Nama:</b> $post_name<br /><b>Kontak:</b> $post_fbid<br /><b>Level:</b> $post_level";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
				}
			}
		}

	include("../../lib/header.php");
?>

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="pull-left page-title"></h4>
                            </div>
                        </div>
								<div class="row">
									<div class="col-md-12">
								<div class="panel panel-border panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title"><i class="fa fa-plus"></i> Add user</h3> 
                                    </div> 
                                    <div class="panel-body">
										<?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-icon alert-success alert-dismissible fade in" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										} else if ($msg_type == "error") {
										?>
										<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group row">
												<label class="col-md-2 control-label">Level</label>
												<div class="col-md-10">
													<select class="form-control" name="level">
														<option value="Reseller">Reseller</option>
														<option value="Admin">Admin</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-md-2 control-label">Name</label>
												<div class="col-md-10">
													<input type="text" name="name" class="form-control" placeholder="Name">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-md-2 control-label">Kontak</label>
												<div class="col-md-10">
													<input type="text" name="fbid" class="form-control" placeholder="Kontak">
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/staff.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Kembali ke daftar</a>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Ulangi</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="add">Tambah</button>
											</div>
										</form>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
	include("../../lib/footer.php");
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>