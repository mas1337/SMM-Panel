<?php
require("../mainconfig.php");

if (isset($_POST['category'])) {
	$post_cat = $_POST['category'];
	$check_service = mysqli_query($db, "SELECT * FROM services WHERE category = '$post_cat' AND status = 'Active' ORDER BY sid ASC");
	?>
	<option value="0">Select one...</option>
	<?php
	while ($data_service = mysqli_fetch_assoc($check_service)) {
	?>
	<option value="<?php echo $data_service['sid'];?>"><?php echo $data_service['service'];?></option>
	<?php
	}
} else {
?>
<option value="0">Error.</option>
<?php
}