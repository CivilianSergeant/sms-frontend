<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = 'dashboard/error';
$route['translate_uri_dashes'] = FALSE;

$route['access-denied'] = 'test/access_denied';
$route['license-missing'] = 'test/license_missing';

$route['login']       = 'authenticate/login'; 
$route['refresh-captcha'] = 'authenticate/refresh_captcha';
$route['authenticate']  = 'authenticate/post_login';
$route['user-registration']  = 'authenticate/user_registration';
$route['user-permission']  = 'authenticate/user_permission';
$route['check-status'] = 'authenticate/check_status';
/**** User Role ****/
$route['user-role']        = 'role/index';
$route['user-role/ajax_get_roles'] = 'role/ajax_get_roles';
$route['user-role/create'] = 'role/create';
$route['user-role/edit/(:any)'] = 'role/edit/$1';
$route['user-role/delete/(:any)'] = 'role/delete/$1';
$route['user-role/ajax_get_role/(:any)'] = 'role/ajax_get_role/$1';
$route['user-role/update'] = 'role/update';
$route['user-role/ajax_get_permissions'] = 'role/ajax_get_permissions';

$route['auth/logout'] = 'authenticate/logout';
$route['permissions'] = 'permission/index';
$route['permissions/ajax-get-roles'] = 'permission/ajax_get_roles';
$route['permissions/ajax-get-menu-routes'] = 'permission/ajax_get_menu_routes';
$route['permissions/toggle'] = 'permission/toggle';
$route['permissions/toggle_create'] = 'permission/toggle_create';
$route['permissions/toggle_edit']   = 'permission/toggle_edit';
$route['permissions/toggle_delete'] = 'permission/toggle_delete';
$route['permissions/(:any)'] = 'permission/index/$1';

/**** Profile ****/

/*$route['save-subscriber'] = 'profile/get_subscriber_profile_data';*/
$route['mso'] = 'mso/index';
$route['mso/view/(:any)'] = 'mso/view/$1';
$route['mso/edit/(:any)'] = 'mso/edit/$1';
$route['mso/update/(:any)'] = 'mso/update/$1';
$route['mso/update_profile'] = 'mso/update_profile';
$route['mso/location/ajax_get_request/(:any)'] = 'location/ajax_get_request/$1';
/**** LCO ****/

$route['lco'] = 'lco/index';
$route['lco/import'] = 'lco/import';
$route['lco/import-lco'] = 'lco/import_lco';
$route['lco/ajax_load_profiles'] = 'lco/ajax_load_profiles';
$route['lco/update_login_info'] = 'lco/update_login_info';
$route['lco/create_profile'] = 'lco/create_profile';
$route['lco/create_login_info'] = 'lco/create_login_info';
$route['lco/update_modality'] = 'lco/update_modality';
$route['lco/upload_photo'] = 'lco/upload_photo';
$route['lco/upload_identity'] = 'lco/upload_identity';
$route['lco/ajax_load_region'] = 'lco/ajax_load_region';
$route['lco/save_billing_address'] = 'lco/save_billing_address';
$route['lco/save_business_region'] = 'lco/save_business_region';
$route['lco/view/(:any)'] = 'lco/view/$1';
$route['lco/edit/(:any)'] = 'lco/edit/$1';
$route['lco/update/(:any)'] = 'lco/update/$1';
$route['lco/ajax_get_profile/(:any)'] = 'lco/ajax_get_profile/$1';

$route['lco/location/ajax_get_request/(:any)'] = 'lco/ajax_get_request/$1';

/**** Money Receipt ****/
$route['assign-money-receipt'] = 'money_receipt/index';
$route['assign-money-receipt/ajax_get_collectors']   = 'money_receipt/ajax_get_collectors';
$route['assign-money-receipt/ajax_get_permissions']  = 'money_receipt/ajax_get_permissions';
$route['assign-money-receipt/assign_bulk_receipt']   = 'money_receipt/assign_bulk_receipt';
$route['assign-money-receipt/assign_single_receipt'] = 'money_receipt/assign_single_receipt';

/*** Assign STB ****/
$route['lco-assign-stb'] = 'lco/assign_stb';
$route['lco-assign-stb/ajax_load_assign_stb_data'] = 'lco/ajax_load_assign_stb_data';
$route['lco-assign-stb/search_stb'] = 'lco/search_stb';
$route['lco-assign-stb/assign_stb_to_lco'] = 'lco/assign_stb_to_lco';
$route['lco-assign-stb/ajax_get_permissions'] = 'lco/ajax_get_assign_stb_permission';

/*** Assign Card ****/
$route['lco-assign-card'] = 'lco/assign_card';
$route['lco-assign-card/ajax_load_assign_smartcard_data'] = 'lco/ajax_load_assign_smartcard_data';
$route['lco-assign-card/search_smartcard'] = 'lco/search_smartcard';
$route['lco-assign-card/assign_smartcard_to_lco'] = 'lco/assign_smartcard_to_lco';
$route['lco-assign-card/ajax_get_permissions'] = 'lco/ajax_get_assign_card_permission';

/*** Assign Deivce ****/
$route['assign-device'] = 'lco/assign_device';
$route['assign-device/ajax_load_assign_stb_data'] = 'lco/ajax_load_assign_stb_data';
$route['assign-device/search_device'] = 'lco/search_device';
$route['assign-device/assign_device_to_lco'] = 'lco/assign_device_to_lco';
$route['assign-device/ajax_get_permissions'] = 'lco/ajax_get_assign_stb_permission';

/**** View for MSO OF LCO-(Users & Subscribers) ****/
$route['lco-users'] = 'lco/lco_users';
$route['lco-users/ajax_load_assign_stb_data'] = 'lco/ajax_load_assign_stb_data';
$route['lco-users/ajax_load_staff_profiles'] = 'lco/ajax_load_staff_profiles';

$route['lco-subscribers'] = 'lco/lco_subscribers';
$route['lco-subscribers/view/(:any)'] = 'subscriber/view/$1';
$route['lco-subscribers/load_region'] = 'subscriber/load_region';
$route['lco-subscribers/ajax_get_balance'] = 'subscriber/ajax_get_balance';
$route['lco-subscribers/ajax_get_unused_cards'] = 'subscriber/ajax_get_unused_cards';
$route['lco-subscribers/ajax_get_packages/(:any)'] = 'subscriber/ajax_get_packages/$1';
$route['lco-subscribers/ajax_get_profile/(:any)'] = 'subscriber/ajax_get_profile/$1';
$route['lco-subscribers/ajax_load_assign_stb_data'] = 'lco/ajax_load_assign_stb_data';
$route['lco-subscribers/ajax_load_subscriber_profiles'] = 'lco/ajax_load_subscriber_profiles';
$route['lco-subscribers/ajax-load-groups'] = 'lco/ajax_load_groups';
$route['lco-subscribers/expired-packages/(:any)'] = 'subscriber/expired_packages/$1';
$route['lco-subscribers/ajax_get_packages/(:any)'] = 'subscriber/ajax_get_packages/$1';
$route['lco-subscribers/ajax_get_profile/(:any)'] = 'subscriber/ajax_get_profile/$1';
$route['lco-subscribers/ajax-load-lco/(:any)/(:any)'] = 'lco/ajax_load_lco/$1/$2';
$route['lco-subscribers/download/(:any)/(:any)'] = 'lco/download_subscriber_list/$1/$2';

/**** Subscriber *****/
$route['subscriber'] = 'subscriber/index';
$route['subscriber/import'] = 'subscriber/import';
$route['subscriber/import-subscriber'] = 'subscriber/import_subscriber';
$route['subscriber/view/(:any)'] = 'subscriber/view/$1';
$route['subscriber/edit/(:any)'] = 'subscriber/edit/$1';
$route['subscriber/update/(:any)'] = 'subscriber/update/$1';
$route['subscriber/ajax_get_lco_profile/(:any)'] = 'subscriber/ajax_get_lco_profile/$1';
$route['subscriber/ajax-get-region'] = 'lco/ajax_load_region';
$route['subscriber/location/ajax_get_request/(:any)'] = 'subscriber/ajax_get_request/$1';
$route['subscriber/assign-stb-smartcard'] = 'subscriber/assign_stb_smartcard';
$route['subscriber/charge'] = 'billing/charge';
$route['subscriber/package/(:any)'] = 'subscriber/package_details/$1';
$route['subscriber/migrate/(:any)'] = 'package_migration/subscriber/$1';
$route['subscriber/ajax-get-subscriber-migration-amount'] = 'package_migration/ajax_get_subscriber_migration_amount';
$route['subscriber/package-reassign/(:any)'] = 'package_migration/package_reassign/$1';
$route['subscriber/package-reassign/(:any)/(:any)'] = 'package_migration/package_reassign/$1/$2';
$route['subscriber/save_reassign_packages'] = 'package_migration/save_reassign_packages';
$route['subscriber/unsubscribe'] = 'package_migration/unsubscribe_package';
$route['subscriber/recharge-all'] = 'subscriber/recharge_all';
$route['subscriber/save-recharge-all'] = 'subscriber/save_all_recharge_data';
$route['subscriber/bulk-renew'] = 'subscriber/bulk_renew';
$route['subscriber/subscriber-renew'] = 'subscriber/subscriber_renew';

$route['subscriber/send-authorization-request'] = 'subscriber/send_authorization';
$route['subscriber/send-pair-request'] = 'subscriber/send_pair';
$route['subscriber/send-unpair-request'] = 'subscriber/send_unpair';
$route['subscriber/ajax_load_region'] = 'subscriber/ajax_load_region';
$route['expired-packages/(:any)'] = 'subscriber/expired_packages/$1';
$route['subscriber/download/(:any)'] = 'subscriber/download_subscriber_list/$1';



/****** FOC Subscriber *******/
$route['foc-subscriber'] = 'foc_subscriber/index';
$route['foc-subscriber/ajax_get_permissions'] = 'foc_subscriber/ajax_get_permissions';
$route['foc-subscriber/ajax_get_profile/(:any)'] = 'foc_subscriber/ajax_get_profile/$1';
$route['foc-subscriber/ajax_load_lco_profile'] = 'foc_subscriber/ajax_load_lco_profile';
$route['foc-subscriber/ajax_load_profiles'] = 'foc_subscriber/ajax_load_profiles';
$route['foc-subscriber/view/(:any)'] = 'foc_subscriber/view/$1';
$route['foc-subscriber/edit/(:any)'] = 'foc_subscriber/edit/$1';
$route['foc-subscriber/update/(:any)'] = 'foc_subscriber/update/$1';
$route['foc-subscriber/create_profile'] = 'foc_subscriber/create_profile';
$route['foc-subscriber/update_profile'] = 'foc_subscriber/update_profile';
$route['foc-subscriber/create_login_info'] = 'foc_subscriber/create_login_info';
$route['foc-subscriber/update_login_info'] = 'foc_subscriber/update_login_info';
$route['foc-subscriber/save_billing_address'] = 'foc_subscriber/save_billing_address';
$route['foc-subscriber/upload_photo']  = 'foc_subscriber/upload_photo';
$route['foc-subscriber/upload_identity'] = 'foc_subscriber/upload_identity';
$route['foc-subscriber/upload_subscription_copy'] = 'foc_subscriber/upload_subscription_copy';
$route['foc-subscriber/ajax-get-lco'] = 'foc_subscriber/ajax_get_lco';
$route['foc-subscriber/ajax-get-references'] = 'foc_subscriber/ajax_get_references';
$route['foc-subscriber/ajax_get_packages/(:any)'] = 'foc_subscriber/ajax_get_packages/$1';
$route['foc-subscriber/ajax_get_balance'] = 'foc_subscriber/ajax_get_balance';
$route['foc-subscriber/ajax_get_assigned_packages'] = 'foc_subscriber/ajax_get_assigned_packages';
$route['foc-subscriber/ajax_get_unused_cards'] = 'foc_subscriber/ajax_get_unused_cards';
$route['foc-subscriber/assign-stb-smartcard'] = 'foc_subscriber/assign_stb_smartcard';
$route['foc-subscriber/save_assign_packages'] = 'foc_subscriber/save_assign_packages';
$route['foc-subscriber/has_package_assigned'] = 'foc_subscriber/has_package_assigned';
$route['foc-subscriber/ajax_load_region'] = 'foc_subscriber/ajax_load_region';
$route['foc-subscriber/save_foc_reassign_packages'] = 'package_migration/save_foc_reassign_packages';
$route['foc-subscriber/package/(:any)'] = 'foc_subscriber/package_details/$1';
$route['foc-subscriber/save_business_region'] = 'foc_subscriber/save_business_region';
$route['foc-subscriber/migrate/(:any)'] = 'package_migration/foc_subscriber/$1';
$route['foc-subscriber/ajax-get-foc-migration-amount'] = 'package_migration/ajax_get_foc_migration_amount';
$route['foc-subscriber/package-reassign/(:any)'] = 'package_migration/foc_package_reassign/$1';
$route['foc-subscriber/package-reassign/(:any)/(:any)'] = 'package_migration/foc_package_reassign/$1/$2';
$route['foc-subscriber/unsubscribe'] = 'package_migration/foc_unsubscribe_package';
$route['foc-subscriber/location/ajax_get_request/(:any)'] = 'location/ajax_get_request/$1';
$route['foc-subscriber/send-authorization-request'] = 'foc_subscriber/send_authorization';
$route['foc-subscriber/send-pair-request'] = 'foc_subscriber/send_pair';
$route['foc-subscriber/send-unpair-request'] = 'foc_subscriber/send_unpair';

/****Parking Zone****/
$route['park-subscriber'] = 'parking_zone/index';
$route['park-subscriber/ajax-get-subscribers'] = 'parking_zone/ajax_get_subscribers';
$route['park-subscriber/park'] = 'parking_zone/park';
$route['park-subscriber/ajax-get-pairing-id/(:any)'] = 'parking_zone/ajax_get_pairing_id/$1';
$route['assign-from-parking']='parking_zone/assign_from_parking';
$route['assign-from-parking/ajax-get-subscribers'] = 'parking_zone/ajax_get_subscribers';
$route['assign-from-parking/ajax-get-parks'] = 'parking_zone/ajax_get_parks';
$route['assign-from-parking/reassign'] = 'parking_zone/reassign';
$route['ownership-transfer'] = 'parking_zone/ownership_transfer';
$route['ownership-transfer/ajax-get-subscribers'] = 'parking_zone/ajax_get_subscribers';
$route['ownership-transfer/ajax-get-pairing-id/(:any)'] = 'parking_zone/ajax_get_pairing_id/$1';
$route['ownership-transfer/transfer'] = 'parking_zone/transfer';

/**** package routes ****/
$route['package'] = 'package/index';
$route['package/view/(:any)'] = 'package/view/$1';
$route['package/edit/(:any)'] = 'package/edit/$1';
$route['package/save'] = 'package/save_package';
$route['package/save/(:any)'] = 'package/update_package/$1';
$route['package/assign-details/(:any)'] = 'package/assign_details/$1';

/**** Add-on Packages ****/
$route['add-on-package'] = 'Add_on_package/index';
$route['add-on-package/view/(:any)'] = 'add_on_package/view/$1';
$route['add-on-package/edit/(:any)'] = 'add_on_package/edit/$1';
$route['add-on-package/save']        = 'add_on_package/save_package';
$route['add-on-package/save/(:any)'] = 'add_on_package/update_package/$1';
$route['add-on-package/assign'] = 'add_on_package/assign';
$route['add-on-package/delete/(:any)'] = 'add_on_package/delete/$1';
$route['add-on-package/assign-details/(:any)'] = 'add_on_package/assign_details/$1';
$route['add-on-package/ajax_load_programs'] = 'add_on_package/ajax_load_programs';
$route['add-on-package/ajax_load_package'] = 'add_on_package/ajax_load_package';
$route['add-on-package/ajax_load_package_programs/(:any)'] = 'add_on_package/ajax_load_package_programs/$1';
$route['add-on-package/ajax_get_assigned_package_list/(:any)'] = 'add_on_package/ajax_get_assigned_package_list/$1';
$route['add-on-package/ajax_load_subscribers'] = 'add_on_package/ajax_load_subscribers';
$route['add-on-package/ajax_pairing_id'] = 'add_on_package/ajax_pairing_id';
$route['add-on-package/save_assign_packages'] = 'add_on_package/save_assign_packages';
$route['add-on-package/ajax_load_profiles'] = 'add_on_package/ajax_load_profiles';
$route['add-on-package/ajax_get_permissions'] = 'add_on_package/ajax_get_permissions';

$route['add-on-package-assign'] = 'add_on_package/assign';
$route['add-on-package-assign/ajax_load_subscribers'] = 'add_on_package/ajax_load_subscribers';
$route['add-on-package-assign/ajax_load_package'] = 'add_on_package/ajax_load_package';
$route['add-on-package-assign/ajax_pairing_id'] = 'add_on_package/ajax_pairing_id';
$route['add-on-package-assign/save_assign_packages'] = 'add_on_package/save_assign_packages';
$route['add-on-package-assign/ajax_get_balance'] = 'subscriber/ajax_get_balance';


$route['add-on-package-subscriber'] = 'add_on_package/subscriber';
$route['add-on-package-subscriber/ajax_load_profiles'] = 'add_on_package/ajax_load_profiles';
/***** program *****/
$route['program']='program/index';
$route['program/save-program']='program/save_program';
$route['edit-view/(:any)']='program/edit_view/$1';
$route['program/update']='program/updateprogram';
$route['program/export-programs'] = 'program/export_programs';


/*** Business Region ***/
$route['region'] = 'region/index';
$route['region/create'] = 'region/create';
$route['region/update'] = 'region/update';

/**** Setting routes ****/
$route['app-setting'] = 'dashboard/app_setting';


/*****locatiion *****/
$route['locaiton'] = 'location/index';
$route['location/save-location'] = 'location/save_location';
$route['location/new-district'] = 'location/new_district';
$route['location/save-district'] = 'location/save_district';


/***** organization *****/
$route['organization'] = 'Organization_info/index';
$route['organization/default-logo/(:num)'] = 'Organization_info/default_logo/$1';
$route['organization/upload-logo'] = 'Organization_info/upload_logo';
$route['organization/default-hls/(:num)'] = 'Organization_info/default_hls/$1';
$route['organization/update-hls'] = 'Organization_info/update_hls';
$route['organization/update-other-url'] = 'Organization_info/update_other_url';
$route['organization/save-organization'] = 'Organization_info/save_organaization';
$route['organization/update-organization/(:any)'] = 'Organization_info/update_organaization/$1';


/***** IC Smartcard Provider *****/
$route['icsmartcard-provider']='icsmartcard_provider/index';
$route['icsmartcard-provider/ajax_get_permissions'] = 'icsmartcard_provider/ajax_get_permissions';
$route['icsmartcard-provider/ajax_load_providers'] = 'icsmartcard_provider/ajax_load_providers';
$route['icsmartcard-provider/create']='icsmartcard_provider/create';
$route['icsmartcard-provider/view/(:any)']='icsmartcard_provider/view_icsmartcard_proprovider/$1';
$route['icsmartcard-provider/edit/(:any)']='icsmartcard_provider/edit_icsmartcard_provider/$1';
$route['icsmartcard-provider/update-action']='icsmartcard_provider/update_action';

/***** Set-Top Box Provider*****/
$route['stb-provider']='stb_provider/index';
$route['stb-provider/ajax_get_permissions'] = 'stb_provider/ajax_get_permissions';
$route['stb-provider/ajax_load_providers'] = 'stb_provider/ajax_load_providers';
$route['stb-provider/create']='stb_provider/create_provider';
$route['stb-provider/view/(:any)']='stb_provider/view_stb_provider/$1';
$route['stb-provider/edit/(:any)']='stb_provider/edit_stb_provider/$1';
$route['stb-provider/update-action']='stb_provider/update_action';


/***** IC Smartcard *****/
$route['icsmart-card']='icsmart_card/index';
$route['icsmart-card/testExNum'] = 'icsmart_card/testExNum';
$route['icsmart-card/ajax_get_permissions'] = 'icsmart_card/ajax_get_permissions';
$route['icsmart-card/ajax_load_ic_smartcards'] = 'icsmart_card/ajax_load_ic_smartcards';
$route['icsmart-card/create']='icsmart_card/create_ic_smartcard';
$route['icsmart-card/view/(:any)']='icsmart_card/view_ic_smartcard/$1';
$route['icsmart-card/edit/(:any)']='icsmart_card/edit_ic_smartcard/$1';
$route['icsmart-card/update-action']='icsmart_card/update_action';
$route['icsmart-card/export']='icsmart_card/export_template';
$route['icsmart-card/import']='icsmart_card/import_cards';

/***** Set-Top Box*****/
$route['set-top-box']='settop_box/index';
$route['set-top-box/ajax_get_permissions'] = 'settop_box/ajax_get_permissions';
$route['set-top-box/ajax_load_stb'] = 'settop_box/ajax_load_stb';
$route['set-top-box/create']='settop_box/create_stb';
$route['set-top-box/view/(:any)']='settop_box/view_stb/$1';
$route['set-top-box/edit/(:any)']='settop_box/edit_set_top_box/$1';
$route['set-top-box/update-action']='settop_box/update_action';
$route['set-top-box/export-stb']='settop_box/export_stb';
$route['set-top-box/import-stb']='settop_box/import_stb';

/***** Change-password*****/
$route['change-password']='Change_password/index';
$route['update-password']='Change_password/change_pass';

/*****Forget_password*****/
$route['forget-password']='Forget_password/index';


/***** Payments *******/
$route['payment']='payments/index';
$route['refund'] ='payments/refund';

/***** Scratch *******/

$route['card-generate'] = 'scratch_card/index';
$route['card-distribute'] = 'scratch_card/card_distribution';
$route['card-report'] = 'scratch_card/card_report';


/*Billing*/
/*Billing*/
$route['cash'] = 'billing/cash';
$route['cash/ajax_load_subscribers'] = 'billing/ajax_load_subscribers';
$route['cash/pairing_id'] = 'billing/pairing_id';
$route['cash/save_cash_receive'] = 'billing/save_cash_receive';
$route['cash/ajax_get_assigned_packages'] = 'billing/ajax_get_assigned_packages';
$route['cash/ajax-get-subscriber-balance'] = 'subscriber/ajax_get_balance';
$route['cash/(:any)'] = 'billing/cash/$1';
$route['subscriber/charge/(:any)/(:any)'] = 'billing/subscriber_packages/$1/$2';
$route['foc-subscriber/charge/(:any)/(:any)'] = 'billing/subscriber_packages/$1/$2';

/*$route['lco-payments-cash'] = 'billing/cash';
$route['lco-payments-cash/ajax_load_subscribers'] = 'billing/ajax_load_subscribers';
$route['lco-payments-cash/pairing_id'] = 'billing/pairing_id';
$route['lco-payments-cash/save_cash_receive'] = 'billing/save_cash_receive';
$route['lco-payments-cash/ajax_get_assigned_packages'] = 'billing/ajax_get_assigned_packages';
$route['lco-payments-cash/ajax-get-subscriber-balance'] = 'subscriber/ajax_get_balance';
$route['lco-payments-cash/(:any)'] = 'billing/cash/$1';
$route['subscriber/charge/(:any)/(:any)'] = 'billing/subscriber_packages/$1/$2';*/


/*Collector*/
$route['collector'] = 'collector/index';
$route['collector/view/(:any)'] = 'collector/view/$1';
$route['collector/edit/(:any)'] = 'collector/edit/$1';
$route['collector/save-collector'] = 'collector/save_collector';
$route['collector/update-collector'] = 'collector/update';

/**** Tools ***/
$route['tools-send-notification'] = 'Send_notification/index';
$route['tools-send-notification/ajax-get-device-groups'] = 'Send_notification/ajax_get_device_groups';
$route['tools-send-notification/send-fcm-notification'] = 'Send_notification/send_fcm_notification';
$route['tools-send-notification/ajax-get-devices/(:num)'] = 'Send_notification/ajax_get_devices/$1';

$route['tools-conditional-mail']   = 'Conditional_mail/index';
$route['tools-conditional-mail/ajax-get-lco'] = 'Conditional_mail/ajax_get_lco';
$route['tools-conditional-mail/ajax-get-subscriber-by-lco/(:any)'] = 'Conditional_mail/ajax_get_subscriber_by_lco/$1';
$route['tools-conditional-mail/ajax-get-pairing/(:any)'] = 'Conditional_mail/ajax_get_pairing/$1';
$route['tools-conditional-mail/ajax-get-regions'] = 'Conditional_mail/ajax_get_regions';
$route['tools-conditional-mail/process-mail'] = 'Conditional_mail/process_mail';

$route['tools-conditional-search'] = 'Conditional_search/index';
$route['tools-conditional-search/ajax-get-lco'] = 'Conditional_search/ajax_get_lco';
$route['tools-conditional-search/ajax-get-subscriber-by-lco/(:any)'] = 'Conditional_search/ajax_get_subscriber_by_lco/$1';
$route['tools-conditional-search/ajax-get-pairing/(:any)'] = 'Conditional_search/ajax_get_pairing/$1';
$route['tools-conditional-search/ajax-get-regions'] = 'Conditional_search/ajax_get_regions';
$route['tools-conditional-search/process-search'] = 'Conditional_search/process_search';

$route['tools-pair-stb-ic'] = 'Pair_stb_ic/index';
$route['tools-pair-stb-ic/ajax-get-lco'] = 'Pair_stb_ic/ajax_get_lco';
$route['tools-pair-stb-ic/ajax-get-subscriber-by-lco/(:any)'] = 'Pair_stb_ic/ajax_get_subscriber_by_lco/$1';
$route['tools-pair-stb-ic/ajax-get-pairing/(:any)'] = 'Pair_stb_ic/ajax_get_pairing/$1';
$route['tools-pair-stb-ic/ajax-get-regions'] = 'Pair_stb_ic/ajax_get_regions';
$route['tools-pair-stb-ic/process-pairing']  = 'Pair_stb_ic/process_pairing';

$route['tools-scrolling-osd'] = 'Scrolling_osd/index';
$route['tools-scrolling-osd/ajax-get-lco'] = 'Scrolling_osd/ajax_get_lco';
$route['tools-scrolling-osd/ajax-get-subscriber-by-lco/(:any)'] = 'Scrolling_osd/ajax_get_subscriber_by_lco/$1';
$route['tools-scrolling-osd/ajax-get-pairing/(:any)'] = 'Scrolling_osd/ajax_get_pairing/$1';
$route['tools-scrolling-osd/ajax-get-regions'] = 'Scrolling_osd/ajax_get_regions';
$route['tools-scrolling-osd/ajax-get-settings-data'] = 'Scrolling_osd/ajax_get_settings_data';
$route['tools-scrolling-osd/process'] = 'Scrolling_osd/process';

$route['tools-force-osd'] = 'Force_osd/index';
$route['tools-force-osd/ajax-get-lco'] = 'Force_osd/ajax_get_lco';
$route['tools-force-osd/ajax-get-subscriber-by-lco/(:any)'] = 'Force_osd/ajax_get_subscriber_by_lco/$1';
$route['tools-force-osd/ajax-get-pairing/(:any)'] = 'Force_osd/ajax_get_pairing/$1';
$route['tools-force-osd/ajax-get-regions'] = 'Force_osd/ajax_get_regions';
$route['tools-force-osd/ajax-get-settings-data'] = 'Force_osd/ajax_get_settings_data';
$route['tools-force-osd/ajax-get-programs'] = 'Force_osd/ajax_get_programs';
$route['tools-force-osd/process'] = 'Force_osd/process';

$route['tools-conditional-limited'] = 'Conditional_limited/index';
$route['tools-conditional-limited/ajax-get-lco'] = 'Conditional_limited/ajax_get_lco';
$route['tools-conditional-limited/ajax-get-subscriber-by-lco/(:any)'] = 'Conditional_limited/ajax_get_subscriber_by_lco/$1';
$route['tools-conditional-limited/ajax-get-pairing/(:any)'] = 'Conditional_limited/ajax_get_pairing/$1';
$route['tools-conditional-limited/ajax-get-regions'] = 'Conditional_limited/ajax_get_regions';
$route['tools-conditional-limited/ajax-get-packages'] = 'Conditional_limited/ajax_get_packages';
$route['tools-conditional-limited/process'] = 'Conditional_limited/process';

$route['tools-ecm-fingerprint'] = 'Ecm_fingerprint/index';
$route['tools-ecm-fingerprint/ajax-get-lco'] = 'Ecm_fingerprint/ajax_get_lco';
$route['tools-ecm-fingerprint/ajax-get-subscriber-by-lco/(:any)'] = 'Ecm_fingerprint/ajax_get_subscriber_by_lco/$1';
$route['tools-ecm-fingerprint/ajax-get-pairing/(:any)'] = 'Ecm_fingerprint/ajax_get_pairing/$1';
$route['tools-ecm-fingerprint/ajax-get-regions'] = 'Ecm_fingerprint/ajax_get_regions';
$route['tools-ecm-fingerprint/ajax-get-programs'] = 'Ecm_fingerprint/ajax_get_programs';
$route['tools-ecm-fingerprint/process'] = 'Ecm_fingerprint/process';

$route['tools-emm-fingerprint'] = 'Emm_fingerprint/index';
$route['tools-emm-fingerprint/ajax-get-lco'] = 'Emm_fingerprint/ajax_get_lco';
$route['tools-emm-fingerprint/ajax-get-subscriber-by-lco/(:any)'] = 'Emm_fingerprint/ajax_get_subscriber_by_lco/$1';
$route['tools-emm-fingerprint/ajax-get-pairing/(:any)'] = 'Emm_fingerprint/ajax_get_pairing/$1';
$route['tools-emm-fingerprint/ajax-get-regions'] = 'Emm_fingerprint/ajax_get_regions';
$route['tools-emm-fingerprint/ajax-get-settings-data'] = 'Emm_fingerprint/ajax_get_settings_data';
$route['tools-emm-fingerprint/process'] = 'Emm_fingerprint/process';

/****** Reports ****/
$route['reports-system-log'] = 'reports/index';
$route['reports-system-log/ajax-get-lco'] = 'Conditional_mail/ajax_get_lco';
$route['reports-system-log/ajax-load-mail-logs'] = 'reports/ajax_load_mail_logs';
$route['reports-system-log/ajax-get-subscriber-by-lco/(:any)'] = 'Conditional_mail/ajax_get_subscriber_by_lco/$1';
$route['reports-system-log/ajax-load-cards/(:any)'] = 'reports/ajax_load_cards/$1';
$route['reports-system-log/stop_mail'] = 'Conditional_mail/stop_mail';
/*****Access_denied*****/
$route['access-denied']='Test/access_denied';
$route['no-permission']='test/no_permission';

/**** Recharge ****/
$route['subscriber-recharge'] = 'recharge/index';
$route['subscriber-recharge'] = 'recharge/index';
$route['subscriber-recharge/ajax_load_payment_methods'] = 'recharge/ajax_load_payment_methods';
$route['subscriber/subscriber-recharge/ajax_load_payment_methods'] = 'recharge/ajax_load_payment_methods';
$route['foc-subscriber/subscriber-recharge/ajax_load_payment_methods'] = 'recharge/ajax_load_payment_methods';

$route['subscriber-recharge/ajax_load_subscribers'] = 'recharge/ajax_load_subscribers';
$route['subscriber/subscriber-recharge/ajax_load_subscribers'] = 'recharge/ajax_load_subscribers';
$route['foc-subscriber/subscriber-recharge/ajax_load_subscribers'] = 'recharge/ajax_load_subscribers';

$route['subscriber-recharge/ajax_get_payment_url'] = 'recharge/ajax_get_payment_url';
$route['subscriber/subscriber-recharge/ajax_get_payment_url'] = 'recharge/ajax_get_payment_url';
$route['foc-subscriber/subscriber-recharge/ajax_get_payment_url'] = 'recharge/ajax_get_payment_url';
/*$route['subscriber-recharge/(:any)'] = 'recharge/subscriber/$1';*/
$route['subscriber/subscriber-recharge/(:any)'] = 'recharge/subscriber/$1';
$route['foc-subscriber/subscriber-recharge/(:any)'] = 'recharge/subscriber/$1';

/**** Scratch Card ****/
$route['scratch-card-distributor'] = 'distributor/index';
$route['scratch-card-distributor/save-distributor'] = 'distributor/save_distributor';
$route['scratch-card-distributor/ajax-load-distributor'] = 'distributor/ajax_load_distributor';
$route['scratch-card-distributor/distributor-view/(:any)'] = 'distributor/distributor_view/$1';
$route['scratch-card-distributor/distributor-edit/(:any)'] = 'distributor/distributor_edit/$1';
$route['scratch-card-distributor/distributor-update'] = 'distributor/distributor_update';
$route['scratch-card-distributor/ajax-get-permissions'] = 'distributor/ajax_get_permissions';

$route['scratch-card-generate'] = 'scratch_card/index';
$route['scratch-card-generate/ajax-download-request'] = 'scratch_card/ajax_download_request';
$route['scratch-card-generate/ajax-get-permissions'] = 'scratch_card/ajax_get_permissions';
$route['scratch-card-generate/save-cards'] = 'scratch_card/save_cards';
$route['scratch-card-generate/download-pdf'] = 'scratch_card/download_pdf';
$route['scratch-card-generate/ajax-load-cards'] = 'scratch_card/ajax_load_cards';
$route['scratch-card-generate/card-view/(:any)'] = 'scratch_card/card_view/$1';
$route['scratch-card-generate/card-edit/(:any)'] = 'scratch_card/card_edit/$1';
$route['scratch-card-generate/card-update'] = 'scratch_card/card_update';
$route['scratch-card-generate/change-status'] = 'scratch_card/change_status';
$route['scratch-card-generate/scratch-card-batch-info/(:any)'] = 'scratch_card/scratch_card_batch_info/$1';
$route['scratch-card-generate/ajax-load-all-cards'] = 'scratch_card/ajax_load_all_cards';
$route['scratch-card-generate/ajax-load-all-cards-by-cardno-serialno'] = 'scratch_card/ajax_load_cards_by_cardno_serialno';
$route['scratch-card-generate/download/(:any)'] = 'scratch_card/download/$1';

/*$route['scratch-card-distribution'] = 'card_distribution/index';
$route['scratch-card-distribution/ajax-load-lco'] = 'card_distribution/ajax_get_lco';
$route['scratch-card-distribution/ajax-load-groups'] = 'card_distribution/ajax_get_groups';
$route['scratch-card-distribution/ajax-load-distributors-by-lco/(:any)'] = 'card_distribution/ajax_load_distributor_by_lco/$1';
$route['scratch-card-distribution/ajax-get-permissions'] = 'card_distribution/ajax_get_permissions';
$route['scratch-card-distribution/distribution-card'] = 'card_distribution/distribution_card';
$route['scratch-card-distribution/ajax-load-batch-numbers/(:any)'] = 'card_distribution/ajax_load_batch_numbers/$1';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)'] = 'card_distribution/ajax_load_serial_no/$1';
$route['scratch-card-distribution/ajax-load-lco/(:any)/(:any)'] = 'card_distribution/ajax_get_lco/$1/$2';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)/(:any)'] = 'card_distribution/ajax_load_serial_no/$1/$2';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)/(:any)/(:any)'] = 'card_distribution/ajax_load_serial_no/$1/$2/$3';*/

$route['scratch-card-available'] = 'scratch_card/available_list';
$route['scratch-card-available/ajax-load-cards'] = 'scratch_card/ajax_load_cards';
$route['scratch-card-available/card-view/(:num)'] = 'scratch_card/available_card_view/$1';
$route['scratch-card-available/ajax-get-cards/(:num)/(:num)'] = 'scratch_card/ajax_get_cards/$1/$2';
$route['scratch-card-available/detail/(:num)/(:num)'] = 'scratch_card/available_card_detail/$1/$2';


$route['scratch-card-distribution'] = 'card_distribution/index';
$route['scratch-card-distribution/ajax-load-lco'] = 'card_distribution/ajax_get_lco';
$route['scratch-card-distribution/ajax-load-groups'] = 'card_distribution/ajax_get_groups';
$route['scratch-card-distribution/ajax-load-distributors-by-lco/(:any)'] = 'card_distribution/ajax_load_distributor_by_lco/$1';
$route['scratch-card-distribution/ajax-get-permissions'] = 'card_distribution/ajax_get_permissions';
$route['scratch-card-distribution/ajax-get-distributed-list'] = 'card_distribution/ajax_get_distributed_list';
$route['scratch-card-distribution/distribution-card'] = 'card_distribution/distribution_card';
$route['scratch-card-distribution/card-view/(:num)'] = 'scratch_card/available_card_view/$1';
$route['scratch-card-distribution/ajax-load-batch-numbers/(:any)'] = 'card_distribution/ajax_load_batch_numbers/$1';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)'] = 'card_distribution/ajax_load_serial_no/$1';
$route['scratch-card-distribution/ajax-get-cards/(:num)/(:num)'] = 'scratch_card/ajax_get_cards/$1/$2';
$route['scratch-card-distribution/detail/(:num)/(:num)'] = 'scratch_card/available_card_detail/$1/$2';
$route['scratch-card-distribution/ajax-load-lco/(:any)/(:any)'] = 'card_distribution/ajax_get_lco/$1/$2';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)/(:any)'] = 'card_distribution/ajax_load_serial_no/$1/$2';
$route['scratch-card-distribution/ajax-load-serial-no/(:any)/(:any)/(:any)'] = 'card_distribution/ajax_load_serial_no/$1/$2/$3';


$route['bank-accounts'] = 'Bank_account/index';
$route['bank-accounts/create'] = 'Bank_account/create';
$route['bank-accounts/view/(:any)'] = 'Bank_account/view/$1';
$route['bank-accounts/edit/(:any)'] = 'Bank_account/edit/$1';
$route['bank-accounts/share/(:any)'] = 'Bank_account/share/$1';
$route['bank-accounts/update'] = 'Bank_account/update';
$route['bank-accounts/share-account'] = 'Bank_account/share_account';
$route['bank-accounts/ajax-get-shared-accounts'] ='Bank_account/ajax_get_shared_accounts';
$route['bank-accounts/ajax-get-accounts'] = 'Bank_account/ajax_get_accounts';
$route['bank-accounts/ajax-get-bank-account-details/(:any)'] = 'Bank_account/ajax_get_bank_account_details/$1';
$route['bank-accounts/ajax-get-permissions'] = 'Bank_account/ajax_get_permissions';
$route['bank-accounts/ajax-get-account-info'] = 'Bank_account/ajax_get_account_info';
$route['bank-accounts/ajax-get-lco'] = 'Bank_account/ajax_get_lco';

$route['pos-settings'] = 'Pos_settings/index';
$route['pos-settings/create'] = 'Pos_settings/create';
$route['pos-settings/view/(:any)'] = 'Pos_settings/view/$1';
$route['pos-settings/edit/(:any)'] = 'Pos_settings/edit/$1';
$route['pos-settings/update'] = 'Pos_settings/update';
$route['pos-settings/ajax-get-pos-machine/(:any)'] = 'Pos_settings/ajax_get_pos_machine/$1';
$route['pos-settings/ajax-get-permissions'] = 'Pos_settings/ajax_get_permissions';
$route['pos-settings/ajax-get-pos-machines'] = 'Pos_settings/ajax_get_pos_machines';
$route['pos-settings/ajax-get-collectors/(:any)'] = 'collector/ajax_get_collectors_by_lco/$1';

$route['pos-payment'] = 'Pos_payment/index';

$route['pos-payment/payment'] = 'Pos_payment/payment';
$route['pos-payment/ajax-get-lco'] = 'Pos_payment/ajax_get_lco';
$route['pos-payment/ajax-get-pos/(:any)'] = 'Pos_payment/ajax_get_pos/$1';
$route['pos-payment/ajax-get-collectors/(:any)'] = 'Pos_payment/ajax_get_collectors_by_lco/$1';
$route['pos-payment/ajax-get-subscriber/(:any)'] = 'Pos_payment/ajax_get_subscribers/$1';
$route['pos-payment/ajax-get-pairing-id/(:any)'] = 'Pos_payment/ajax_get_pairing_id/$1';
$route['pos-payment/(:any)'] = 'Pos_payment/index/$1';

$route['bank-payment'] = 'Bank_payment/index';
$route['bank-payment/payment'] = 'Bank_payment/payment';
$route['bank-payment/ajax-get-accounts'] = 'Bank_payment/ajax_get_accounts';
$route['bank-payment/ajax-get-subscriber/(:any)'] = 'Bank_payment/ajax_get_subscribers/$1';
$route['bank-payment/ajax-get-pairing-id/(:any)'] = 'Bank_payment/ajax_get_pairing_id/$1';
$route['bank-payment/(:any)']='Bank_payment/index/$1';

$route['payments-online'] = 'Online_payment/index';
$route['payments-online/ajax-get-lco'] = 'Online_payment/ajax_get_lco';
$route['payments-online/ajax-get-subscriber/(:any)'] = 'Online_payment/ajax_get_subscribers/$1';
$route['payments-online/ajax-get-pairing-id/(:any)'] = 'Online_payment/ajax_get_pairing_id/$1';

$route['payments-scratch-card'] = 'Scratch_payment/index';
$route['payments-scratch-card/ajax-get-lco'] = 'Scratch_payment/ajax_get_lco';
$route['payments-scratch-card/ajax-get-serial-cards'] = 'Scratch_payment/ajax_get_serial_cards';
$route['payments-scratch-card/ajax-get-subscriber/(:any)'] = 'Scratch_payment/ajax_get_subscribers/$1';
$route['payments-scratch-card/ajax-get-pairing-id/(:any)'] = 'Scratch_payment/ajax_get_pairing_id/$1';
$route['payments-scratch-card/payment'] = 'Scratch_payment/payment';
$route['payments-scratch-card/(:any)'] = 'Scratch_payment/index/$1';


$route['payments-bkash'] = 'Bkash_payment/index';
$route['payments-bkash/ajax-get-lco'] = 'Bkash_payment/ajax_get_lco';
$route['payments-bkash/ajax-get-subscriber/(:any)'] = 'Bkash_payment/ajax_get_subscribers/$1';
$route['payments-bkash/ajax-get-pairing-id/(:any)'] = 'Bkash_payment/ajax_get_pairing_id/$1';
$route['payments-bkash/payment'] = "Bkash_payment/payment";
$route['payments-bkash/(:any)'] = 'Bkash_payment/index/$1';

$route['payments-gift-voucher'] = 'Gift_voucher/index';
$route['payments-gift-voucher/ajax-get-lco'] = 'Online_payment/ajax_get_lco';
$route['payments-gift-voucher/ajax-get-subscriber/(:any)'] = 'Online_payment/ajax_get_subscribers/$1';
$route['payments-gift-voucher/ajax-get-pairing-id/(:any)'] = 'Online_payment/ajax_get_pairing_id/$1';

$route['payments-refund'] = 'Refund/index';
//$route['lco-payments-refund'] = 'Refund/index';

$route['client-statements'] = 'Reports/client_statements';
$route['client-statements/ajax-get-lco'] = 'Reports/ajax_get_lco';
$route['client-statements/ajax-get-subscriber-by-lco/(:any)'] = 'Reports/ajax_get_subscriber_by_lco/$1';
$route['client-statements/ajax-get-pairings/(:any)'] = 'Reports/ajax_get_pairings/$1';
$route['client-statements/ajax-get-statements'] = 'Reports/ajax_get_statements';

$route['collection-statement'] = 'Reports/collection_statements';
$route['collection-statement/ajax-get-lco'] = 'Reports/ajax_get_collection_lco';
$route['collection-statement/ajax-get-collection-statements'] = 'Reports/ajax_get_collection_statements';

$route['foc-client-statement'] = 'Foc_statement/index';
$route['foc-client-statement/ajax-get-subscriber-by-lco/(:any)'] = 'Foc_statement/ajax_get_subscriber_by_lco/$1';
$route['foc-client-statement/ajax-get-pairings/(:any)']  = 'Foc_statement/ajax_get_pairings/$1';
$route['foc-client-statement/ajax-get-statements'] = 'Foc_statement/ajax_get_statements';

$route['foc-collection'] = 'Foc_statement/collection_statements';
$route['foc-collection/ajax-get-collection-statements'] = 'Foc_statement/ajax_get_collection_statements';

$route['bank-statement'] = 'Bank_statement/index';
$route['bank-statement/ajax-get-bank-accounts'] = 'Bank_statement/ajax_get_bank_accounts';
$route['bank-statement/ajax-get-statements'] = 'Bank_statement/ajax_get_statements';

$route['pos-statement'] = 'Pos_statement/index';
$route['pos-statement/ajax-get-pos'] = 'Pos_statement/ajax_get_pos';
$route['pos-statement/ajax-get-statements'] = 'Pos_statement/ajax_get_statements';

$route['collector-statement'] = 'Collector_statement/index';
$route['collector-statement/ajax-get-collectors'] = 'Collector_statement/ajax_get_collectors';
$route['collector-statement/ajax-get-statements'] = 'Collector_statement/ajax_get_statements';

$route['cash-statement'] = 'Cash_statement/index';
$route['cash-statement/ajax-get-statements'] = 'Cash_statement/ajax_get_statements';

$route['notification-report'] = 'notification/report';
$route['notification/ajax-get-report']= 'notification/ajax_get_report';

$route['parking-report'] = 'Parking_report/index';
$route['parking-report/ajax-get-data'] = 'Parking_report/ajax_get_data';
$route['parking-report/ajax-get-parking-report'] = 'Parking_report/ajax_get_parking_report';

$route['reassign-pairing-report'] = 'Parking_report/reassign_pairing_report';
$route['reassign-pairing-report/ajax-get-data'] = 'Parking_report/ajax_get_data';
$route['reassign-pairing-report/ajax-get-reassign-report'] = 'Parking_report/ajax_get_reassign_report';

$route['ownership-transfer-report'] = 'Parking_report/ownership_transfer';
$route['ownership-transfer-report/ajax-get-data'] = 'Parking_report/ajax_get_data';
$route['ownership-transfer-report/ajax-get-transfer-report'] = 'Parking_report/ajax_get_transfer_report';

/****** dashboard ******/
$route['dashboard/ajax-get-available-packages'] = 'dashboard/ajax_get_available_packages';

/******* Subscriber Portal routes ********/

$route['profile/ajax-load-region'] = 'profile/ajax_load_region';
$route['profile/ajax-get-unused-cards'] = 'profile/ajax_get_unused_cards';
$route['profile/ajax-get-balance'] = 'profile/ajax_get_balance';
$route['profile/payment'] = 'profile/payment';
$route['profile/ajax-get-subscriber-migration-amount'] = 'profile/ajax_get_subscriber_migration_amount';
$route['profile/unsubscribe'] = 'profile/unsubscribe_package';
$route['profile/save-reassign-packages'] = 'profile/save_reassign_packages';
$route['profile/save-addon-package'] = 'profile/save_add_on_package';
$route['my-transactions/ajax-get-statements'] = 'My_transactions/ajax_get_statements';
$route['profile/package-reassign/(:any)'] = 'profile/package_reassign/$1';
$route['profile/package-reassign/(:any)/(:any)'] = 'profile/package_reassign/$1/$2';
$route['subscriber-packages/(:any)'] =  'profile/packages/$1';
$route['subscriber-addon-packages/(:any)'] = 'profile/add_on_packages/$1';
$route['profile/ajax-get-location/(:any)'] = 'profile/ajax_get_location/$1';
$route['profile/ajax-get-packages/(:any)'] = 'profile/ajax_get_packages/$1';
$route['profile/ajax-get-assigned-addon-packages/(:any)'] = 'profile/ajax_get_addon_packages/$1';
$route['profile/ajax-load-addon-packages/(:any)'] = 'profile/ajx_get_addon_packages/$1';
$route['profile/ajax-get-pairing-id/(:any)'] = 'profile/ajax_get_pairing_id/$1';
$route['profile/edit/(:any)'] = 'subscriber/edit/$1';
$route['profile/foc-edit/(:any)'] = 'foc-subscriber/edit/$1';
$route['profile/(:any)']='profile/index/$1';
/*$route['user-info/(:any)']='profile/user_info/$1';*/
$route['user-documents/(:any)']='profile/user_documents/$1';
$route['subscription-info/(:any)']='profile/subscription_info/$1';
/*$route['billing-info/(:any)']='profile/billing_info/$1';*/
$route['subscription-scratch-recharge/(:any)']='profile/recharge_account/$1';
$route['subscription-online-recharge/(:any)']='profile/online_recharge/$1';
$route['my-transactions/(:any)'] = 'my_transactions/index/$1';

/********** Group Accounts **********/
$route['groups'] = 'group/index';
$route['groups/ajax-get-permissions'] = 'group/ajax_get_permissions';
$route['groups/ajax-load-profiles']   = 'group/ajax_load_profiles';
$route['groups/ajax-get-lco']   = 'group/ajax_get_lco';
$route['groups/assign-lco-to-group'] = 'group/assign_lco_to_group';
$route['groups/create-profile'] = 'group/create_profile';
$route['groups/update-profile']  = 'group/update_profile';
$route['groups/create-login-info'] = 'group/create_login_info';
$route['groups/update-login-info'] = 'group/update_login_info';
$route['groups/upload-photo']    = 'group/upload_photo';
$route['groups/upload-identity'] = 'group/upload_identity';
$route['groups/update-modality'] = 'group/upload_modality';
$route['groups/view/(:any)']    = 'group/view/$1';
$route['groups/edit/(:any)']    = 'group/edit/$1';
$route['groups/ajax-get-lco-by-group/(:any)'] = 'group/ajax_get_lco_by_group/$1';
$route['groups/ajax-get-profile/(:any)'] = 'group/ajax_get_profile/$1';
$route['groups/ajax-get-location-request/(:any)'] = 'location/ajax_get_request/$1';

$route['assign-lco'] = 'group/assign_lco';
$route['assign-lco/ajax-get-group-lco/(:num)'] = 'group/ajax_get_group_lco/$1';

/****** DB Backup ********/
$route['backup'] = "backup/index";
$route['backup/ajax-check-password'] = 'backup/ajax_check_password';
$route['backup/ajax-dump-file-list'] = 'backup/ajax_dump_file_list';
$route['backup/dump'] = 'backup/dump';
$route['backup/ajax-get-ftp-accounts'] = 'backup/ajax_get_ftp_accounts';
$route['backup/transfer'] = 'backup/transfer';
$route['backup/transfer-file/(:any)'] = 'backup/transfer_file/$1';

$route['db-backup-logs'] = 'backup/backup_logs';
$route['db-backup-logs/ajax-get-db-backup-logs'] = 'backup/ajax_get_db_backup_logs';

/***** FTP Accounts *****/
$route['ftp-accounts'] = 'Ftp_account/index';
$route['ftp-accounts/ajax-get-permissions'] = 'Ftp_account/ajax_get_permissions';
$route['ftp-accounts/ajax-get-accounts'] = 'Ftp_account/ajax_get_accounts';
$route['ftp-accounts/ajax-get-account-info'] = 'Ftp_account/ajax_get_account_info';
$route['ftp-accounts/create'] = 'Ftp_account/create';
$route['ftp-accounts/update'] = 'Ftp_account/update';
$route['ftp-accounts/delete'] = 'Ftp_account/delete';
$route['ftp-accounts/edit/(:any)'] = 'Ftp_account/edit/$1';
$route['ftp-accounts/view/(:any)'] = 'Ftp_account/view/$1';



/*************** IPTV ***********/
$route['live-delay-categories'] = 'Iptv_categories/index';
$route['live-delay-categories/ajax-check-password'] = 'Iptv_categories/ajax_check_password';
$route['live-delay-categories/ajax-get-categories'] = 'Iptv_categories/ajax_get_categories';
$route['live-delay-categories/ajax-get-programs'] = 'Iptv_categories/ajax_get_programs';
$route['live-delay-categories/save-category'] = 'Iptv_categories/save_category';
$route['live-delay-categories/assign-program-category'] = 'Iptv_categories/assign_program_category';
$route['live-delay-categories/delete-category-program'] = 'Iptv_categories/delete_category_program';
$route['live-delay-categories/delete-category/(:num)'] = 'Iptv_categories/delete_category/$1';
$route['live-delay-categories/delete-sub-category/(:num)'] = 'Iptv_categories/delete_sub_category/$1';
$route['live-delay-categories/ajax-get-sub-categories/(:num)'] = 'Iptv_categories/ajax_get_sub_categories/$1';
$route['live-delay-categories/ajax-get-selected-programs/(:num)'] = 'Iptv_categories/ajax_get_selected_programs/$1';

$route['live-categories'] = 'Iptv_categories/index';
$route['live-categories/ajax-get-categories'] = 'Iptv_categories/ajax_get_categories';
$route['live-categories/ajax-get-programs'] = 'Iptv_categories/ajax_get_programs';
$route['live-categories/save-category'] = 'Iptv_categories/save_category';
$route['live-categories/assign-program-category'] = 'Iptv_categories/assign_program_category';
$route['live-categories/delete-category-program'] = 'Iptv_categories/delete_category_program';
$route['live-categories/delete-category/(:num)'] = 'Iptv_categories/delete_category/$1';
$route['live-categories/delete-sub-category/(:num)'] = 'Iptv_categories/delete_sub_category/$1';
$route['live-categories/ajax-get-sub-categories/(:num)'] = 'Iptv_categories/ajax_get_sub_categories/$1';
$route['live-categories/ajax-get-selected-programs/(:num)'] = 'Iptv_categories/ajax_get_selected_programs/$1';

$route['delay-categories'] = 'Iptv_categories/index';
$route['delay-categories/ajax-get-categories'] = 'Iptv_categories/ajax_get_categories';
$route['delay-categories/ajax-get-programs'] = 'Iptv_categories/ajax_get_programs';
$route['delay-categories/save-category'] = 'Iptv_categories/save_category';
$route['delay-categories/assign-program-category'] = 'Iptv_categories/assign_program_category';
$route['delay-categories/delete-category-program'] = 'Iptv_categories/delete_category_program';
$route['delay-categories/delete-category/(:num)'] = 'Iptv_categories/delete_category/$1';
$route['delay-categories/delete-sub-category/(:num)'] = 'Iptv_categories/delete_sub_category/$1';
$route['delay-categories/ajax-get-sub-categories/(:num)'] = 'Iptv_categories/ajax_get_sub_categories/$1';
$route['delay-categories/ajax-get-selected-programs/(:num)'] = 'Iptv_categories/ajax_get_selected_programs/$1';


$route['catchup-categories'] = 'Iptv_categories/index';
$route['catchup-categories/ajax-get-categories'] = 'Iptv_categories/ajax_get_categories';
$route['catchup-categories/ajax-get-programs'] = 'Iptv_categories/ajax_get_programs';
$route['catchup-categories/ajax-check-password'] = 'Iptv_categories/ajax_check_password';
$route['catchup-categories/save-category'] = 'Iptv_categories/save_category';
$route['catchup-categories/assign-program-category'] = 'Iptv_categories/assign_program_category';
$route['catchup-categories/delete-category-program'] = 'Iptv_categories/delete_category_program';
$route['catchup-categories/delete-category/(:num)'] = 'Iptv_categories/delete_category/$1';
$route['catchup-categories/delete-sub-category/(:num)'] = 'Iptv_categories/delete_sub_category/$1';
$route['catchup-categories/ajax-get-sub-categories/(:num)'] = 'Iptv_categories/ajax_get_sub_categories/$1';
$route['catchup-categories/ajax-get-selected-programs/(:num)'] = 'Iptv_categories/ajax_get_selected_programs/$1';


$route['app-categories'] = 'Iptv_categories/app_categories';
$route['app-categories/ajax-get-app-categories'] = 'Iptv_categories/ajax_get_app_categories';
$route['app-categories/save-app-categories'] = 'Iptv_categories/save_app_category';
$route['app-categories/edit/(:num)'] = 'Iptv_categories/edit_app_category/$1';
$route['app-categories/ajax-get-appcat-data'] = 'Iptv_categories/get_appcat_data';
$route['app-categories/view/(:num)'] = 'Iptv_categories/view_app_category/$1';
$route['app-categories/update'] = 'Iptv_categories/update_app_category';
$route['app-categories/get-programs'] = 'Iptv_categories/ajax_get_all_programs';
$route['app-categories/search-programs'] = 'Iptv_categories/search_programs';

$route['vod-categories'] = 'Iptv_categories/index';
$route['vod-categories/ajax-get-categories'] = 'Iptv_categories/ajax_get_categories';
$route['vod-categories/ajax-get-programs'] = 'Iptv_categories/ajax_get_programs';
$route['vod-categories/ajax-check-password'] = 'Iptv_categories/ajax_check_password';
$route['vod-categories/save-category'] = 'Iptv_categories/save_category';
$route['vod-categories/assign-program-category'] = 'Iptv_categories/assign_program_category';
$route['vod-categories/delete-category-program'] = 'Iptv_categories/delete_category_program';
$route['vod-categories/delete-category/(:num)'] = 'Iptv_categories/delete_category/$1';
$route['vod-categories/delete-sub-category/(:num)'] = 'Iptv_categories/delete_sub_category/$1';
$route['vod-categories/ajax-get-sub-categories/(:num)'] = 'Iptv_categories/ajax_get_sub_categories/$1';
$route['vod-categories/ajax-get-selected-programs/(:num)'] = 'Iptv_categories/ajax_get_selected_programs/$1';


/*$route['subscriber'] = 'Iptv_subscribers/index';
$route['subscriber/ajax-get-profiles'] = 'Iptv_subscribers/ajax_get_profiles';
$route['subscriber/create-profile'] = 'Iptv_subscribers/create_profile';
$route['subscriber/update-profile'] = 'Iptv_subscribers/update_profile';
$route['subscriber/create-login-info'] = 'Iptv_subscribers/create_login_info';
$route['subscriber/update-login-info'] = 'Iptv_subscribers/update_login_info';
$route['subscriber/save-billing-address'] = 'Iptv_subscribers/save_billing_address';
$route['subscriber/upload-photo'] = 'Iptv_subscribers/upload_photo';
$route['subscriber/upload-identity'] = 'Iptv_subscribers/upload_identity';
$route['subscriber/upload-subscription-copy'] = 'Iptv_subscribers/upload_subscription_copy';
$route['subscriber/ajax-get-balance'] = 'Iptv_subscribers/ajax_get_balance';
$route['subscriber/ajax-get-assigned-packages'] = 'Iptv_subscribers/ajax_get_assigned_packages';
$route['subscriber/ajax-get-unused-cards'] = 'Iptv_subscribers/ajax_get_unused_cards';
$route['subscriber/assign-stb-smartcard'] = 'Iptv_subscribers/assign_stb_smartcard';
$route['subscriber/save-assign-packages'] = 'Iptv_subscribers/save_assign_packages';
$route['subscriber/ajax-load-region'] = 'Iptv_subscribers/ajax_load_region';
$route['subscriber/ajax-load-lco-profile'] = 'Iptv_subscribers/ajax_load_lco_profile';
$route['subscriber/save-business-region'] = 'Iptv_subscribers/save_business_region';
$route['subscriber/ajax-get-permissions'] = 'Iptv_subscribers/ajax_get_permissions';
$route['subscriber/ajax-get-packages/(:any)'] = 'Iptv_subscribers/ajax_get_packages/$1';
$route['subscriber/location/ajax-get-request/(:any)'] = 'Iptv_subscribers/ajax_get_request/$1';*/

$route['channels'] = 'Iptv_programs/index';
$route['channels/ajax-get-permissions'] = 'Iptv_programs/ajax_get_permissions';
$route['channels/ajax-get-programs'] = 'Iptv_programs/ajax_get_programs';
$route['channels/ajax-get-lco'] = 'Iptv_programs/ajax_get_lco';
$route['channels/save-program'] = 'Iptv_programs/save_program';
$route['channels/update-program'] = 'Iptv_programs/update_program';

$route['channel-sort'] = 'Channel_sort/index';
$route['channel-sort/update-program-order'] = 'Channel_sort/update_program_order';
$route['channel-sort/update-program-status'] = 'Channel_sort/update_program_status';

$route['channels/upload-image'] = 'Iptv_programs/upload_image';
$route['channels/upload-channel-logo'] = 'Iptv_programs/upload_channel_logo';
/*$route['channels/upload-logo-mobile'] = 'Iptv_programs/upload_logo_mobile';*/
/*$route['channels/upload-poster-stb'] = 'Iptv_programs/upload_poster_stb';
$route['channels/upload-poster-mobile'] = 'Iptv_programs/upload_poster_mobile';*/
$route['channels/upload-water-mark'] = 'Iptv_programs/upload_water_mark';
$route['channels/save-mapping'] = 'Iptv_programs/save_mapping';
$route['channels/remove-mapping'] = 'Iptv_programs/remove_mapping';
$route['channels/delete'] = 'Iptv_programs/delete';
$route['channels/download-epg'] = 'Iptv_programs/download_epg';
$route['channels/ajax-get-program/(:num)'] = 'Iptv_programs/ajaxGetProgram/$1';
$route['channels/ajax-get-sub-categories/(:num)'] = 'Iptv_programs/ajaxGetSubCategories/$1';
$route['channels/sync-epg/(:num)'] = 'Iptv_programs/sync_epg/$1';
$route['channels/edit/(:num)'] = 'Iptv_programs/edit/$1';
$route['channels/view/(:num)'] = 'Iptv_programs/view/$1';
$route['channels/mapping/(:num)'] = 'Iptv_programs/mapping/$1';
$route['channels/ajax-get-streamer-instance/(:num)'] = 'Iptv_programs/ajax_get_streamer_instance_by_lco/$1';

/**** VoD Programs ***/
$route['vod-programs'] = 'Vod_programs/index';
$route['vod-programs/ajax-get-permissions'] = 'Vod_programs/ajax_get_permissions';
$route['vod-programs/ajax-get-programs'] = 'Vod_programs/ajax_get_programs';
$route['vod-programs/ajax-get-lco'] = 'Vod_programs/ajax_get_lco';
$route['vod-programs/save-program'] = 'Vod_programs/save_program';
$route['vod-programs/update-program'] = 'Vod_programs/update_program';
$route['vod-programs/upload-image'] = 'Vod_programs/upload_image';
/*$route['vod-programs/upload-logo'] = 'Vod_programs/upload_logo';
$route['vod-programs/upload-logo-mobile'] = 'Vod_programs/upload_logo_mobile';
$route['vod-programs/upload-poster-stb'] = 'Vod_programs/upload_poster_stb';
$route['vod-programs/upload-poster-mobile'] = 'Vod_programs/upload_poster_mobile';*/
$route['vod-programs/upload-water-mark'] = 'Vod_programs/upload_water_mark';
$route['vod-programs/save-mapping'] = 'Vod_programs/save_mapping';
$route['vod-programs/remove-mapping'] = 'Vod_programs/remove_mapping';
$route['vod-programs/delete'] = 'Vod_programs/delete';
$route['vod-programs/ajax-get-program/(:num)'] = 'Vod_programs/ajax_get_program/$1';
$route['vod-programs/ajax-get-sub-categories/(:num)'] = 'Vod_programs/ajaxGetSubCategories/$1';
$route['vod-programs/edit/(:num)'] = 'Vod_programs/edit/$1';
$route['vod-programs/view/(:num)'] = 'Vod_programs/view/$1';
$route['vod-programs/mapping/(:num)'] = 'Vod_programs/mapping/$1';
$route['vod-programs/ajax-get-streamer-instance/(:num)'] = 'Vod_programs/ajax_get_streamer_instance_by_lco/$1';


/*** Catchup Programs ***/
$route['catchup-programs'] = 'Catchup_programs/index';
$route['catchup-programs/ajax-get-permissions'] = 'Catchup_programs/ajax_get_permissions';
$route['catchup-programs/ajax-get-programs'] = 'Catchup_programs/ajax_get_programs';
$route['catchup-programs/ajax-get-lco'] = 'Catchup_programs/ajax_get_lco';
$route['catchup-programs/save-program'] = 'Catchup_programs/save_program';
$route['catchup-programs/update-program'] = 'Catchup_programs/update_program';
$route['catchup-programs/upload-image'] = 'Catchup_programs/upload_image';
/*$route['catchup-programs/upload-logo-mobile'] = 'Catchup_programs/upload_logo_mobile';
$route['catchup-programs/upload-poster-stb'] = 'Catchup_programs/upload_poster_stb';
$route['catchup-programs/upload-poster-mobile'] = 'Catchup_programs/upload_poster_mobile';*/
$route['catchup-programs/upload-water-mark'] = 'Catchup_programs/upload_water_mark';
$route['catchup-programs/save-mapping'] = 'Catchup_programs/save_mapping';
$route['catchup-programs/remove-mapping'] = 'Catchup_programs/remove_mapping';
$route['catchup-programs/delete'] = 'Catchup_programs/delete';
$route['catchup-programs/ajax-get-program/(:num)'] = 'Catchup_programs/ajax_get_program/$1';
$route['catchup-programs/ajax-get-sub-categories/(:num)'] = 'Catchup_programs/ajaxGetSubCategories/$1';
$route['catchup-programs/edit/(:num)'] = 'Catchup_programs/edit/$1';
$route['catchup-programs/view/(:num)'] = 'Catchup_programs/view/$1';
$route['catchup-programs/mapping/(:num)'] = 'Catchup_programs/mapping/$1';
$route['catchup-programs/ajax-get-streamer-instance/(:num)'] = 'Catchup_programs/ajax_get_streamer_instance_by_lco/$1';

/*** Serial Contents ***/
$route['serial-contents'] = 'Serial_contents/index';
$route['serial-contents/ajax-get-permissions'] = 'Serial_contents/ajax_get_permissions';
$route['serial-contents/ajax-get-serial-programs'] = 'Serial_contents/ajax_get_serial_programs';
$route['serial-contents/ajax-get-lco'] = 'Serial_contents/ajax_get_lco';
$route['serial-contents/save-program'] = 'Serial_contents/save_program';
$route['serial-contents/update-program'] = 'Serial_contents/update_program';
$route['serial-contents/upload-image'] = 'Serial_contents/upload_image';
/*$route['serial-contents/upload-logo-mobile'] = 'Serial_contents/upload_logo_mobile';
$route['serial-contents/upload-poster-stb'] = 'Serial_contents/upload_poster_stb';
$route['serial-contents/upload-poster-mobile'] = 'Serial_contents/upload_poster_mobile';*/
$route['serial-contents/upload-water-mark'] = 'Serial_contents/upload_water_mark';
$route['serial-contents/save-mapping'] = 'Serial_contents/save_mapping';
$route['serial-contents/remove-mapping'] = 'Serial_contents/remove_mapping';
$route['serial-contents/delete'] = 'Serial_contents/delete';
$route['serial-contents/ajax-get-program/(:num)'] = 'Serial_contents/ajax_get_program/$1';
$route['serial-contents/ajax-get-categories/(:any)'] = 'Serial_contents/ajax_get_categories/$1';
$route['serial-contents/ajax-get-sub-categories/(:num)'] = 'Serial_contents/ajaxGetSubCategories/$1';
$route['serial-contents/edit/(:num)'] = 'Serial_contents/edit/$1';
$route['serial-contents/view/(:num)'] = 'Serial_contents/view/$1';
$route['serial-contents/mapping/(:num)'] = 'Serial_contents/mapping/$1';
$route['serial-contents/ajax-get-streamer-instance/(:num)'] = 'Serial_contents/ajax_get_streamer_instance_by_lco/$1';

$route['live-delay-packages'] = 'Iptv_packages/index';
$route['live-delay-packages/ajax-get-permissions'] = 'Iptv_packages/ajax_get_permissions';
$route['live-delay-packages/ajax-get-categories']= 'Iptv_packages/ajax_get_categories';
$route['live-delay-packages/ajax-get-programs'] = 'Iptv_packages/ajax_get_programs';
$route['live-delay-packages/ajax-get-packages'] = 'Iptv_packages/ajax_get_packages';
$route['live-delay-packages/save-package'] = 'Iptv_packages/save_package';
$route['live-delay-packages/upload-logo-stb'] = 'Iptv_packages/upload_logo_stb';
$route['live-delay-packages/upload-logo-mobile'] = 'Iptv_packages/upload_logo_mobile';
$route['live-delay-packages/upload-poster-stb'] = 'Iptv_packages/upload_poster_stb';
$route['live-delay-packages/upload-poster-mobile'] = 'Iptv_packages/upload_poster_mobile';
$route['live-delay-packages/update-package'] = 'Iptv_packages/update_package';
$route['live-delay-packages/ajax-get-sub-categories/(:num)'] = 'Iptv_packages/ajax_get_sub_categories/$1';
$route['live-delay-packages/ajax-get-package-programs/(:num)'] = 'Iptv_packages/ajax_get_package_programs/$1';
$route['live-delay-packages/edit/(:num)'] = 'Iptv_packages/edit/$1';
$route['live-delay-packages/view/(:num)'] = 'Iptv_packages/view/$1';
$route['live-delay-packages/delete/(:num)'] = 'Iptv_packages/delete/$1';

$route['live-packages'] = 'Iptv_packages/index';
$route['live-packages/ajax-get-permissions'] = 'Iptv_packages/ajax_get_permissions';
$route['live-packages/ajax-get-categories']= 'Iptv_packages/ajax_get_categories';
$route['live-packages/ajax-get-programs'] = 'Iptv_packages/ajax_get_programs';
$route['live-packages/ajax-get-packages'] = 'Iptv_packages/ajax_get_packages';
$route['live-packages/save-package'] = 'Iptv_packages/save_package';
$route['live-packages/upload-logo-stb'] = 'Iptv_packages/upload_logo_stb';
$route['live-packages/upload-logo-mobile'] = 'Iptv_packages/upload_logo_mobile';
$route['live-packages/upload-poster-stb'] = 'Iptv_packages/upload_poster_stb';
$route['live-packages/upload-poster-mobile'] = 'Iptv_packages/upload_poster_mobile';
$route['live-packages/update-package'] = 'Iptv_packages/update_package';
$route['live-packages/ajax-get-sub-categories/(:num)'] = 'Iptv_packages/ajax_get_sub_categories/$1';
$route['live-packages/ajax-get-package-programs/(:num)'] = 'Iptv_packages/ajax_get_package_programs/$1';
$route['live-packages/edit/(:num)'] = 'Iptv_packages/edit/$1';
$route['live-packages/view/(:num)'] = 'Iptv_packages/view/$1';
$route['live-packages/delete/(:num)'] = 'Iptv_packages/delete/$1';

$route['delay-packages'] = 'Iptv_packages/index';
$route['delay-packages/ajax-get-permissions'] = 'Iptv_packages/ajax_get_permissions';
$route['delay-packages/ajax-get-categories']= 'Iptv_packages/ajax_get_categories';
$route['delay-packages/ajax-get-programs'] = 'Iptv_packages/ajax_get_programs';
$route['delay-packages/ajax-get-packages'] = 'Iptv_packages/ajax_get_packages';
$route['delay-packages/save-package'] = 'Iptv_packages/save_package';
$route['delay-packages/upload-logo-stb'] = 'Iptv_packages/upload_logo_stb';
$route['delay-packages/upload-logo-mobile'] = 'Iptv_packages/upload_logo_mobile';
$route['delay-packages/upload-poster-stb'] = 'Iptv_packages/upload_poster_stb';
$route['delay-packages/upload-poster-mobile'] = 'Iptv_packages/upload_poster_mobile';
$route['delay-packages/update-package'] = 'Iptv_packages/update_package';
$route['delay-packages/ajax-get-sub-categories/(:num)'] = 'Iptv_packages/ajax_get_sub_categories/$1';
$route['delay-packages/ajax-get-package-programs/(:num)'] = 'Iptv_packages/ajax_get_package_programs/$1';
$route['delay-packages/edit/(:num)'] = 'Iptv_packages/edit/$1';
$route['delay-packages/view/(:num)'] = 'Iptv_packages/view/$1';
$route['delay-packages/delete/(:num)'] = 'Iptv_packages/delete/$1';

$route['catchup-packages'] = 'Iptv_packages/index';
$route['catchup-packages/ajax-get-permissions'] = 'Iptv_packages/ajax_get_permissions';
$route['catchup-packages/ajax-get-categories']= 'Iptv_packages/ajax_get_categories';
$route['catchup-packages/ajax-get-programs'] = 'Iptv_packages/ajax_get_programs';
$route['catchup-packages/ajax-get-packages'] = 'Iptv_packages/ajax_get_packages';
$route['catchup-packages/save-package'] = 'Iptv_packages/save_package';
$route['catchup-packages/upload-logo-stb'] = 'Iptv_packages/upload_logo_stb';
$route['catchup-packages/upload-logo-mobile'] = 'Iptv_packages/upload_logo_mobile';
$route['catchup-packages/upload-poster-stb'] = 'Iptv_packages/upload_poster_stb';
$route['catchup-packages/upload-poster-mobile'] = 'Iptv_packages/upload_poster_mobile';
$route['catchup-packages/update-package'] = 'Iptv_packages/update_package';
$route['catchup-packages/ajax-get-sub-categories/(:num)'] = 'Iptv_packages/ajax_get_sub_categories/$1';
$route['catchup-packages/ajax-get-package-programs/(:num)'] = 'Iptv_packages/ajax_get_package_programs/$1';
$route['catchup-packages/edit/(:num)'] = 'Iptv_packages/edit/$1';
$route['catchup-packages/view/(:num)'] = 'Iptv_packages/view/$1';
$route['catchup-packages/delete/(:num)'] = 'Iptv_packages/delete/$1';

$route['vod-packages'] = 'Iptv_packages/index';
$route['vod-packages/ajax-get-permissions'] = 'Iptv_packages/ajax_get_permissions';
$route['vod-packages/ajax-get-categories']= 'Iptv_packages/ajax_get_categories';
$route['vod-packages/ajax-get-programs'] = 'Iptv_packages/ajax_get_programs';
$route['vod-packages/ajax-get-packages'] = 'Iptv_packages/ajax_get_packages';
$route['vod-packages/save-package'] = 'Iptv_packages/save_package';
$route['vod-packages/upload-logo-stb'] = 'Iptv_packages/upload_logo_stb';
$route['vod-packages/upload-logo-mobile'] = 'Iptv_packages/upload_logo_mobile';
$route['vod-packages/upload-poster-stb'] = 'Iptv_packages/upload_poster_stb';
$route['vod-packages/upload-poster-mobile'] = 'Iptv_packages/upload_poster_mobile';
$route['vod-packages/update-package'] = 'Iptv_packages/update_package';
$route['vod-packages/ajax-get-sub-categories/(:num)'] = 'Iptv_packages/ajax_get_sub_categories/$1';
$route['vod-packages/ajax-get-package-programs/(:num)'] = 'Iptv_packages/ajax_get_package_programs/$1';
$route['vod-packages/edit/(:num)'] = 'Iptv_packages/edit/$1';
$route['vod-packages/view/(:num)'] = 'Iptv_packages/view/$1';
$route['vod-packages/delete/(:num)'] = 'Iptv_packages/delete/$1';

/****** Streamer Instance ******/
$route['streamer-instance'] = 'Streamer_instance/index';
$route['streamer-instance/ajax-get-permissions'] = 'Streamer_instance/ajax_get_permissions';
$route['streamer-instance/ajax-get-instances'] = 'Streamer_instance/ajax_get_instances';
$route['streamer-instance/ajax-get-lco']  = 'Streamer_instance/ajax_get_lco';
$route['streamer-instance/save'] = 'Streamer_instance/save';
$route['streamer-instance/update'] = 'Streamer_instance/update';
$route['streamer-instance/delete'] = 'Streamer_instance/delete';
$route['streamer-instance/sync'] = 'Streamer_instance/sync';
$route['streamer-instance/edit/(:num)'] = 'Streamer_instance/edit/$1';
$route['streamer-instance/view/(:num)'] = 'Streamer_instance/view/$1';
$route['streamer-instance/ajax-get-instance/(:num)'] = 'Streamer_instance/ajax_get_instance/$1';

/***** Monitor Instance *****/
$route['monitor-instance'] = 'Streamer_instance/monitor';
$route['monitor-instance/ajax-get-monitor-instances'] = 'Streamer_instance/ajax_get_monitor_instances';
$route['monitor-instance/ajax-get-instance-data'] = 'Streamer_instance/ajax_get_instance_data';
/****** Devices *******/
$route['devices']='devices/index';
$route['devices/ajax_get_permissions'] = 'devices/ajax_get_permissions';
$route['devices/ajax_load_stb'] = 'devices/ajax_load_stb';
$route['devices/create']='devices/create_stb';
$route['devices/view/(:any)']='devices/view_stb/$1';
$route['devices/edit/(:any)']='devices/edit_set_top_box/$1';
$route['devices/update-action']='devices/update_action';
$route['devices/export-stb']='devices/export_stb';
$route['devices/import-stb']='devices/import_stb';


/**** EPG ******/
$route['manage-epg'] = 'Epg/index';
$route['manage-epg/ajax-get-permissions'] = 'Epg/ajax_get_permissions';
$route['manage-epg/ajax-get-epgs'] = 'Epg/ajax_get_epgs';
$route['manage-epg/ajax-get-channels'] = 'Epg/ajax_get_channels';
$route['manage-epg/save-epg'] = 'Epg/save_epg';
$route['manage-epg/update-epg'] = 'Epg/update_epg';
$route['manage-epg/edit/(:num)'] = 'Epg/edit/$1';
$route['manage-epg/view/(:num)'] = 'Epg/view/$1';
$route['manage-epg/delete/(:num)'] = 'Epg/delete/$1';
$route['manage-epg/ajax-get-epg/(:num)'] = 'Epg/ajax_get_epg/$1';

/**** EPG Provider ******/
$route['manage-epg-provider'] = 'Epg_provider/index';
$route['manage-epg-provider/save-epg-provider'] = 'Epg_provider/save_epg_provider';
$route['manage-epg-provider/ajax-get-permissions'] = 'Epg_provider/ajax_get_permissions';
$route['manage-epg-provider/ajax-get-epg-providers'] = 'Epg_provider/ajax_get_epg_providers';
$route['manage-epg-provider/edit/(:num)'] = 'Epg_provider/edit/$1';
$route['manage-epg-provider/get-provider-by-id/(:num)'] = 'Epg_provider/get_provider_by_id/$1';
$route['manage-epg-provider/update-epg-provider'] = 'Epg_provider/update_epg_provider';
$route['manage-epg-provider/delete/(:num)'] = 'Epg_provider/delete/$1';
$route['manage-epg-provider/view/(:num)'] = 'Epg_provider/view/$1';

/**** EPG Provider Channel ******/
$route['manage-provider-channel'] = 'Epg_provider_channel/index';
$route['manage-provider-channel/ajax-get-permissions'] = 'Epg_provider/ajax_get_permissions';
$route['manage-provider-channel/save-epg-provider-channel'] = 'Epg_provider_channel/save_epg_provider_channel';
$route['manage-provider-channel/ajax-get-epg-provider-channels'] = 'Epg_provider_channel/ajax_get_epg_provider_channels';

/**** Map Provider Channel ******/
$route['map-provider-channel'] = 'Map_provider_channel/index';
$route['map-provider-channel/save-mapping'] = 'Map_provider_channel/save_mapping';
$route['map-provider-channel/ajax-get-provider-channels/(:num)'] = 'Map_provider_channel/ajax_get_provider_channels/$1';

/***** Sync Epg *******/
$route['sync-epg'] = 'Epg/sync_epg';
$route['sync-epg/ajax-get-mappings'] = 'Epg/ajax_get_mappings';
$rotue['sync-epg/download-epg'] = 'Epg/download_epg';
/**** Feature ADS *****/
$route['feature-ads'] = 'Feature_ads/index';

/**** Feature Content ****/
$route['feature-content'] = 'Feature_content/index';
$route['feature-content/ajax-get-permissions'] = 'Feature_content/ajax_get_permissions';
$route['feature-content/ajax-get-feature-contents'] = 'Feature_content/ajax_get_feature_contents';
$route['feature-content/ajax-get-normal-channel-programs'] = 'Feature_content/ajax_get_channel_programs';
$route['feature-content/ajax-get-normal-catchup-content-programs'] = 'Feature_content/ajax_get_catchup_contents';
$route['feature-content/ajax-get-normal-vod-content-programs']     = 'Feature_content/ajax_get_vod_contents';
$route['feature-content/save-feature-content'] = 'Feature_content/save_feature_content';
$route['feature-content/set-as-normal-content'] = 'Feature_content/set_as_normal_content';

/***** My Members *****/
$route['my-members/ajax-get-profiles'] = 'My_members/ajax_get_profiles';
$route['my-members/create-profile'] = 'My_members/create_profile';
$route['my-members/update-profile'] = 'My_members/update_profile';
$route['my-members/create-login-info'] = 'My_members/create_login_info';
$route['my-members/update-login-info'] = 'My_members/update_login_info';
$route['my-members/view/(:any)'] = 'My_members/view/$1';
$route['my-members/edit/(:any)'] = 'My_members/edit/$1';
$route['my-members/ajax-get-profile/(:any)'] = 'My_members/ajax_get_profile/$1';
/*$route['my-members/save-billing-address'] = 'Iptv_subscribers/save_billing_address';
$route['my-members/upload-photo'] = 'Iptv_subscribers/upload_photo';
$route['my-members/upload-identity'] = 'Iptv_subscribers/upload_identity';
$route['my-members/upload-subscription-copy'] = 'Iptv_subscribers/upload_subscription_copy';
$route['my-members/ajax-get-balance'] = 'Iptv_subscribers/ajax_get_balance';
$route['my-members/ajax-get-assigned-packages'] = 'Iptv_subscribers/ajax_get_assigned_packages';
$route['my-members/ajax-get-unused-cards'] = 'Iptv_subscribers/ajax_get_unused_cards';
$route['my-members/assign-stb-smartcard'] = 'Iptv_subscribers/assign_stb_smartcard';
$route['my-members/save-assign-packages'] = 'Iptv_subscribers/save_assign_packages';
$route['my-members/ajax-load-region'] = 'Iptv_subscribers/ajax_load_region';
$route['my-members/ajax-load-lco-profile'] = 'Iptv_subscribers/ajax_load_lco_profile';
$route['my-members/save-business-region'] = 'Iptv_subscribers/save_business_region';
$route['my-members/ajax-get-permissions'] = 'My_members/ajax_get_permissions';
$route['my-members/ajax-get-packages/(:any)'] = 'Iptv_subscribers/ajax_get_packages/$1';
*/
$route['my-members/location/ajax-get-request/(:any)'] = 'Iptv_subscribers/ajax_get_request/$1';
$route['my-members/(:any)'] = 'My_members/index/$1';

/**** Content Provider ****/
$route['content-provider'] = 'Content_provider/index';
$route['content-provider/content_aggregator'] = 'Content_provider/ajax_get_content_aggregator';
$route['content-provider/save-content-provider'] = 'Content_provider/save_content_provider';
$route['content-provider/ajax-get-permissions'] = 'Content_provider/ajax_get_permissions';
$route['content-provider/ajax-get-content-providers'] = 'Content_provider/ajax_get_content_providers';
$route['content-provider/edit/(:num)'] = 'Content_provider/edit/$1';
$route['content-provider/ajax-get-content-provider/(:num)'] = 'Content_provider/ajax_get_content_provider/$1';
$route['content-provider/update-content-provider'] = 'Content_provider/update_content_provider';
$route['content-provider/view/(:num)'] = 'Content_provider/view/$1';

$route['content-provider/delete/(:num)'] = 'Content_provider/delete/$1';


/**** Vendor ****/
$route['vendor'] = 'Vendor/index';
$route['vendor/save-vendor'] = 'Vendor/save_vendor';
$route['vendor/ajax-get-permissions'] = 'Vendor/ajax_get_permissions';
$route['vendor/ajax-get-vendors'] = 'Vendor/ajax_get_vendors';
$route['vendor/ajax-get-vendor-by-id/(:any)'] = 'Vendor/ajax_get_vendor_by_id/$1';
$route['vendor/edit/(:num)'] = 'Vendor/edit/$1';
$route['vendor/update-vendor'] = 'Vendor/update_vendor';
$route['vendor/view/(:any)'] = 'Vendor/view/$1';
$route['vendor/delete/(:num)'] = 'Vendor/delete/$1';

/**** Transcoder ****/
$route['transcoder'] = 'Transcoder/index';
$route['transcoder/save-transcoder'] = 'Transcoder/save_transcoder';
$route['transcoder/ajax-get-permissions'] = 'Transcoder/ajax_get_permissions';
$route['transcoder/ajax-get-transcoders'] = 'Transcoder/ajax_get_transcoders';
$route['transcoder/ajax-get-transcoder-by-id/(:any)'] = 'Transcoder/ajax_get_transcoder_by_id/$1';
$route['transcoder/edit/(:num)'] = 'Transcoder/edit/$1';
$route['transcoder/update-transcoder'] = 'Transcoder/update_transcoder';
$route['vendor/view/(:any)'] = 'Vendor/view/$1';
$route['transcoder/delete/(:num)'] = 'Transcoder/delete/$1';



/**** Device Type ****/
$route['device-type'] = 'Device_type/index';
$route['vendor/save-vendor'] = 'Vendor/save_vendor';


/**** Process Image *****/
$route['process-image'] = 'Process_image/index';
$route['upload-image']  = 'Test/upload_image';
$route['download-epg-image/(:num)/(:num)'] = 'Test/download_epg_image/$1/$2';
