<div class="wf-banner updated below-h2">
    <p class="main">
        <li style='color:red;'><strong><?php _e('Your Business is precious! Go Premium!' , 'wf_order_import_export');?></strong></li>
        <strong><?php _e('Order Import Export Plugin Premium version helps you to seamlessly import/export orders into your Woocommerce Store.', 'wf_order_import_export'); ?></strong>
    <p>
        <?php _e('-Filtering options while Export using Order Status, Start Date, End Date, Offset and Limit.', 'wf_order_import_export'); ?><br/>
        <?php _e('-Import orders from any CSV format ( Magento, Shopify, OpenCart etc. ) into your WooComemrce Store using Column Mapping Feature.', 'wf_order_import_export'); ?><br/>
        <?php _e('-Export orders right from the WooCommerce Admin Order Listing page.', 'wf_order_import_export'); ?><br/>
        <?php _e('-Import and Export orders from/to remote location via FTP.', 'wf_order_import_export'); ?><br/>
        <?php _e('-Excellent Support for setting it up!', 'wf_order_import_export'); ?><br/>
    </p>
</p>
<p>
    <a href="http://www.xadapter.com/product/order-import-export-plugin-for-woocommerce/" target="_blank" class="button button-primary"><?php _e('Upgrade to Premium Version', 'wf_csv_import_export'); ?></a>
    <a href="http://orderimportexport.hikeforce.com/wp-admin/admin.php?page=wf_woocommerce_order_im_ex" target="_blank" class="button"><?php _e('Live Demo', 'wf_csv_import_export'); ?></a>
    <a href="http://www.xadapter.com/2016/06/20/setting-up-order-import-export-plugin-for-woocommerce/" target="_blank" class="button"><?php _e('Documentation', 'wf_csv_import_export'); ?></a>
    <a href="<?php echo plugins_url('sample.csv', WF_OrderImpExpCsv_FILE); ?>" target="_blank" class="button"><?php _e('Sample CSV', 'wf_csv_import_export'); ?></a>
</p>
</div>
<style>
    .wf-banner img {
        float: right;
        margin-left: 1em;
        padding: 15px 0
    }
</style>


<div class="tool-box">
    <h3 class="title"><?php _e('Import Orders in CSV Format:', 'wf_order_import_export'); ?></h3>
    <p><?php _e('Import Orders in CSV format from different sources (  from your computer OR from another server via FTP )', 'wf_order_import_export'); ?></p>
    <p class="submit">
        <?php
        $merge_url = admin_url('admin.php?import=woocommerce_wf_order_csv&merge=1');
        $import_url = admin_url('admin.php?import=woocommerce_wf_order_csv');
        ?>
        <a class="button button-primary" id="mylink" href="<?php echo admin_url('admin.php?import=woocommerce_wf_order_csv'); ?>"><?php _e('Import Orders', 'wf_order_import_export'); ?></a>
        &nbsp;
        <input type="checkbox" id="merge" value="0"><?php _e('Merge order if exists', 'wf_order_import_export'); ?> <br>
    </p>
</div>
<script type="text/javascript">
    jQuery('#merge').click(function () {
        if (this.checked) {
            jQuery("#mylink").attr("href", '<?php echo $merge_url ?>');
        } else {
            jQuery("#mylink").attr("href", '<?php echo $import_url ?>');
        }
    });
    jQuery(document).on( 'click', '.notice-dismiss', function() {
		jQuery(this).parents('div.rate-us:first').slideUp();
		jQuery.post('admin.php?page=wf_woocommerce_order_im_ex', {dismiss: true}, function (data) {
		}, 'json');
    });
</script>