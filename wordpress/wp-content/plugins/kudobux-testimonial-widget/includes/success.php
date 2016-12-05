<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 7/19/16
 * Time: 4:47 PM
 */

ini_set('display_errors', 1);
/*
 * After create a user account
 * insert the kudobuzz javascript in the front head
 */


$uid = get_option("kudobuzz_uid");
$uid = (isset($uid) && !empty($uid)) ? $uid : NULL;

if (isset($uid) && $uid != NULL) {
	update_option('kudobuzz_uid', $uid);
} else {
	die('<h3>Error!</h3><div>Missing UID</div>');
}
?>

<style>
	ul {
		padding-left: 35px;
	}
	ul li {
		font-size: 13px !important
	}
</style>
<div class="main-wrapper">
	<div class="main-app-wrapper">
		<div id="title-div">
			KUDOBUZZ
		</div>
		<div class="main-app-content" style="min-height: 440px; margin-bottom: 10px;">

			<div style="margin: 0px auto 10px auto; width: 820px; overflow: hidden;">

				<div  style="padding: 0px 20px; margin: 30px auto">
					<h3>
						<p class="main-title" style="margin-top: 45px; font-size: 30px; color: #585858">Registration Completed Successfully</p>
						<p>Please check the <a style="text-decoration:underline;" href='<?php echo get_admin_url() ?>admin.php?page=InstallationInstruction'>documentation</a> for short codes you can use</p>
						<p>If you need any help reach us at <a style="text-decoration:underline;" href="http://help.kudobuzz.com/" target="_blank">[ help.kudobuzz.com]</a></p>
						<a style="margin-top: 30px" href="https://app.kudobuzz.com/authenticate?email=<?php echo get_option('kudobuzz_email')?>&id=<?php echo get_option('kudobuzz_user_id')?>" target="_blank" class="btn btn-info btn-lg">Start Publishing Your Testimonials Now</a>
					</h3>
				</div>
			</div>
		</div>
		<div id="copyright-div">
			&copy; 2014 Kudobuzz
		</div>
	</div>
</div>
