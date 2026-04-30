
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

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
	<a class="<?php echo navItemClass('suppliers.php', $currentPage); ?>" href="suppliers.php">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="12" height="12" rx="2"/><path d="M5 8h6M8 5v6"/></svg>
		Suppliers
	</a>
	<!-- <div class="nav-item nav-action" id="openProfileModal" role="button" tabindex="0">
		<svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12L6 7l3 3 2-3 3 4"/></svg>
		Profile
	</div> -->
	<div class="nav-spacer"></div>
	<button class="nav-user" type="button" id="openProfileModalSidebar">
		<div class="avatar">AO</div>
		<div>
			<div class="u-name">Ade Oke</div>
			<div class="u-role">Pharmacist</div>
		</div>
	</button>
</aside>

<?php include __DIR__ . '/profile_modal.php'; ?>
