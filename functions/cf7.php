<?php

// -----------
// CONTACT FORM 7 REMOVE SPAN WRAPPER
// -----------
add_filter('wpcf7_autop_or_not', '__return_false');

// -----------
// REMOVING ENUM VALIDATION FOR DROPDOWNS
// -----------
remove_action('wpcf7_swv_create_schema', 'wpcf7_swv_add_select_enum_rules', 20, 2);
