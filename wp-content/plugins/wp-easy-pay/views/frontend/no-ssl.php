<p> <?php _e('Sorry, your website is not using HTTPS. Please click the link below to convert your website to HTTPS.','wp_easy_pay'); ?></p>

<?php
$site_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<a href="<?php echo esc_url($site_url); ?>" class="form-control"><?php _e(' Convert to HTTPS (Secure)','wp_easy_pay');?></a>
