<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Barangay Management System</title>
	<!-- Your CSS files -->
	<link href="<?= base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />
	<style>
		/* =================================================================== */
		/* UI STYLESHEET                                                     */
		/* =================================================================== */

		/* --- 1. CORE LAYOUT & FONT --- */
		body {
			background-color: #f8f9fa;
			font-family: 'Poppins', sans-serif;
			/* <-- THIS LINE APPLIES THE FONT */
		}


		.app-container {
			display: flex;
			height: 100vh;
			overflow: hidden;
		}

		/* --- 2. SIDEBAR STYLES (EXPANDED & COLLAPSED) --- */
		.sidebar {
			width: 250px;
			flex-shrink: 0;
			background-color: #e0f2f1;
			transition: width 0.3s ease-in-out;
			display: flex;
			flex-direction: column;
		}

		.sidebar.collapsed {
			width: 80px;
		}

		/* Logo Styling */
		.sidebar .logo {
			padding: 20px;
			text-align: center;
		}

		.sidebar .logo img {
			max-width: 100%;
			height: auto;
			transition: all 0.3s ease;
		}

		.sidebar.collapsed .logo {
			padding: 15px 10px;
		}

		/* This makes the logo smaller when collapsed */
		.sidebar.collapsed .logo img {
			max-width: 40px;
		}

		


		/* Menu Links Styling */
		.sidebar-menu {
			flex-grow: 1;
			overflow-y: auto;
		}

		.menu-frame {
			display: flex;
			align-items: center;
			padding: 12px 20px;
			transition: padding 0.3s ease-in-out;
		}

		.menu-frame .icon {
			width: 24px;
			height: 24px;
			margin-right: 15px;
			transition: margin 0.3s ease-in-out;
		}

		.menu-frame a {
			text-decoration: none;
			color: #333;
			font-weight: 500;
		}

		.menu-frame p.menu {
			margin: 0;
			white-space: nowrap;
			/* Prevents text from wrapping */
		}

		/* Collapsed State for Menu */
		.sidebar.collapsed .menu-frame {
			justify-content: center;
			padding: 15px 10px;
		}

		.sidebar.collapsed .menu-frame .icon {
			margin-right: 0;
		}

		.sidebar.collapsed p.menu {
			display: none;
		}


		/* Logout Button */
		.logout {
			padding: 20px;
		}

		.sidebar.collapsed .logout .btn-text {
			display: none;
		}

		.sidebar.collapsed .logout .btn {
			/* Center the icon */
			display: flex;
			justify-content: center;
			align-items: center;
		}


		/* --- 3. CONTENT AREA (TOPBAR & MAIN) --- */
		.content-wrapper {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			overflow: hidden;
		}

		.topbar {
			background-color: #ffffff;
			border-bottom: 1px solid #e3e6f0;
			padding: 0.75rem 1.5rem;
			box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
			z-index: 10;
		}

		#sidebarToggle {
			font-size: 1.25rem;
			color: #5a5c69;
			cursor: pointer;
		}

		.main-content {
			flex-grow: 1;
			overflow-y: auto;
			padding: 20px;
		}
	</style>
</head>

<body>
	<div class="app-container">

		<!-- Sidebar with UPDATED Logout Button -->
		<aside class="sidebar" id="sidebar">
			<div class="logo">
				<!-- The image will now resize automatically via CSS -->
				<img src="<?= base_url('assets/image/sidebar/logo.png'); ?>" alt="City Logo" />
			</div>

			<div class="sidebar-menu">
				<?php $user_type = $this->session->userdata('user_type'); ?>

				<!-- Common Links -->
				<div class="menu-frame">
					<img src="<?= base_url('assets/image/sidebar/dash.png'); ?>" alt="dashboard-logo" class="icon" />
					<a href="<?= site_url('dashboard'); ?>">
						<p class="menu">Dashboard</p>
					</a>
				</div>
				<div class="menu-frame">
					<img src="<?= base_url('assets/image/sidebar/family.png'); ?>" alt="family-logo" class="icon" />
					<a href="<?= site_url('famreg'); ?>">
						<p class="menu">Family Lists</p>
					</a>
				</div>
				<div class="menu-frame">
					<img src="<?= base_url('assets/image/sidebar/reports.png'); ?>" alt="reports-logo" class="icon" />
					<a href="<?= site_url('reports'); ?>">
						<p class="menu">Reports</p>
					</a>
				</div>
				<div class="menu-frame">
					<img src="<?= base_url('assets/image/sidebar/resident.png'); ?>" alt="resident-logo" class="icon" />
					<a href="<?= site_url('residents'); ?>">
						<p class="menu">Resident Management</p>
					</a>
				</div>

				<!-- Admin-Only Links -->
				<?php if ($user_type == 'admin'): ?>
					<hr class="mx-3" style="border-top: 1px solid #ccc;">
					<div class="menu-frame">
						<img src="<?= base_url('assets/image/sidebar/family.png'); ?>" alt="secretary-logo" class="icon" />
						<a href="<?= site_url('secretaries'); ?>">
							<p class="menu">Secretaries</p>
						</a>
					</div>
					<div class="menu-frame">
						<img src="<?= base_url('assets/image/sidebar/reports.png'); ?>" alt="reports-logo" class="icon" />
						<a href="<?= site_url('secretary_reports'); ?>">
							<p class="menu">Secretary Reports</p>
						</a>
					</div>
					<div class="menu-frame">
						<img src="<?= base_url('assets/image/sidebar/contact.png'); ?>" alt="logs-logo" class="icon" />
						<a href="<?= site_url('activity_logs'); ?>">
							<p class="menu">Activity Logs</p>
						</a>
					</div>
				<?php endif; ?>

				<!-- Secretary-Only Link -->
				<?php if ($user_type == 'secretary'): ?>
					<div class="menu-frame">
						<img src="<?= base_url('assets/image/sidebar/contact.png'); ?>" alt="contact-logo" class="icon" />
						<a href="<?= site_url('contactsupport'); ?>">
							<p class="menu">Contact Support</p>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- UPDATED LOGOUT BUTTON: Added an icon and a <span> for the text -->
			<div class="logout">
				<a href="<?= site_url('logout'); ?>" class="btn btn-danger btn-block w-100">
					<i class="fas fa-sign-out-alt"></i>
					<span class="btn-text ms-2">Logout</span>
				</a>
			</div>
		</aside>

		<div class="content-wrapper">
			<!-- Topbar with Toggle Button -->
			<nav class="topbar">
				<i class="fas fa-bars" id="sidebarToggle"></i>
			</nav>

			<!-- Main page content will be loaded here -->
			<main class="main-content">