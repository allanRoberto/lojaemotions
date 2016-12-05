<?php
if (isset($kd_uid) && $kd_uid != NULL) {
    ?>

	<style>.main-app-wrapper{width: 96%}</style>
    <div class="main-wrapper">
        <div class="main-app-wrapper">
            <div id="title-div">
                KUDOBUZZ
            </div>
            <div class="main-app-content" style="min-height: 440px; margin-bottom: 10px; text-align: center">

                <div style="margin: 0px auto 10px auto; width: 820px; overflow: hidden;">

                    <div style="padding: 0px 20px; margin: 30px auto">
                        <h3>
                            <p class="main-title" style="margin-top: 45px; font-size: 30px; color: #585858">Welcome to Kudobuzz</p>
                            
                            <p>Click the button below to login</p>
                            <a style="margin-top: 30px" href="http://app.kudobuzz.com/authenticate?email=<?php echo $kudobuzz_email?>&id=<?php echo $kudobuzz_user_id?>" class="btn btn-info btn-lg" target="_blank">Start Publishing Your Testimonials Now</a>
                        </h3>
                    </div>
                </div>
            </div>
            <div id="copyright-div">
                &copy; 2015 Kudobuzz
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            var kd_uid = '<?php echo $kd_uid ?>';
            if (kd_uid == '') {
                var location = '<?php echo get_admin_url() ?>admin.php?page=Signup';
                window.location = location;
            }
        });
    </script>
    <?php
}