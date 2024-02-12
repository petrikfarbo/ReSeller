<?php
include 'reseller_form_submission.php';
function fr_reseller_admin_init() {
    add_action('admin_post_fr_reseller_form_submission', 'fr_reseller_form_submission');
}