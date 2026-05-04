
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentUserName = trim((string) ($_SESSION['user_name'] ?? 'Ade Oke'));
$currentUserRole = trim((string) ($_SESSION['user_role'] ?? 'Pharmacist'));

function userInitials(string $name): string
{
	$parts = preg_split('/\s+/', trim($name));
	$initials = '';

	foreach (array_slice($parts, 0, 2) as $part) {
		$initials .= strtoupper(substr($part, 0, 1));
	}

	return $initials ?: 'AO';
}

function navItemClass(string $pageName, string $currentPage): string
{
	return $pageName === $currentPage ? 'nav-item active' : 'nav-item';
}
?>

<aside class="nav">
	<div class="nav-logo">PharmaCare</div>
	<div class="nav-section">Main</div>
	<a class="<?php echo navItemClass('dashboard.php', $currentPage); ?>" href="dashboard.php">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
		Dashboard
	</a>
	<a class="<?php echo navItemClass('inventory.php', $currentPage); ?>" href="inventory.php">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3h12M2 8h12M2 13h8"/></svg>
		Inventory
	</a>
	<a class="<?php echo navItemClass('patients.php', $currentPage); ?>" href="patients.php">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
		Patients
	</a>
	<!-- <div class="nav-item nav-action" id="openProfileModal" role="button" tabindex="0">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12L6 7l3 3 2-3 3 4"/></svg>
		Profile
	</div> -->
	<div class="nav-spacer"></div>
	<button class="nav-user" type="button" id="openProfileModalSidebar">
		<div class="avatar"><?php echo htmlspecialchars(userInitials($currentUserName)); ?></div>
		<div>
			<div class="u-name"><?php echo htmlspecialchars($currentUserName); ?></div>
			<div class="u-role"><?php echo htmlspecialchars($currentUserRole); ?></div>
		</div>
	</button>
</aside>

<?php include __DIR__ . '/profile_modal.php'; ?>
