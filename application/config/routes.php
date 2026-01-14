<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- CORE AUTHENTICATION ROUTES ---
$route['default_controller'] = 'AuthController/login';
$route['login'] = 'AuthController/login';
$route['logout'] = 'AuthController/logout';

// --- MAIN APPLICATION ROUTES ---
$route['dashboard'] = 'DashboardController/index';
$route['famreg'] = 'Barangay/famreg'; // The last remaining function in Barangay.php

// --- RESIDENT MANAGEMENT ---
$route['residents'] = 'ResidentController/index';
$route['residents/add'] = 'ResidentController/add';
$route['residents/create'] = 'ResidentController/create';
$route['residents/edit/(:num)'] = 'ResidentController/edit/$1';
$route['residents/update/(:num)'] = 'ResidentController/update/$1';
$route['residents/delete/(:num)'] = 'ResidentController/delete/$1';
$route['get_resident_data'] = 'ResidentController/get_resident_data';
$route['residentmngadd'] = 'ResidentController/add';

// --- SECRETARY MANAGEMENT (ADMIN-ONLY) ---
$route['secretaries'] = 'SecretaryController/index';
$route['secretaries/add'] = 'SecretaryController/add';
$route['secretaries/create'] = 'SecretaryController/create';
$route['secretaries/edit/(:num)'] = 'SecretaryController/edit/$1';
$route['secretaries/update/(:num)'] = 'SecretaryController/update/$1';
$route['secretaries/delete/(:num)'] = 'SecretaryController/delete/$1';
$route['get_secretary_data'] = 'SecretaryController/get_secretary_data';

// --- REPORTING (ADMIN-ONLY) ---
$route['reports'] = 'ReportsController/index'; // Add 's'
$route['reports/display_chart'] = 'ReportsController/display_chart'; // Add 's'
$route['reports/download_report'] = 'ReportsController/download_report'; // Add 's'
$route['reports/get_chart_data'] = 'ReportsController/get_chart_data'; // Add 's'

// --- SUPPORT TICKETS ---
$route['contactsupport'] = 'SupportController/index';
$route['contactsupport/submit'] = 'SupportController/submit';
$route['secretary_reports'] = 'SupportController/reports';
$route['get_secretary_report_data'] = 'SupportController/get_report_data';

// --- ACTIVITY LOGS (ADMIN-ONLY) ---
$route['activity_logs'] = 'LogController/index';
$route['get_log_data'] = 'LogController/get_log_data';

// --- DO NOT CHANGE ---
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE; 