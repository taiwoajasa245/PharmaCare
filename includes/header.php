<?php
$currentUserName = trim((string) ($_SESSION['user_name'] ?? 'Ade Oke'));
$currentUserFirstName = explode(' ', $currentUserName)[0] ?: $currentUserName;
$lowStockBadge = isset($dashboardSummary['lowStockAlerts']) ? (int) $dashboardSummary['lowStockAlerts'] . ' low stock alerts' : 'Loading dashboard...';
?>
<div class="topbar">
	<div class="page-title">Good morning, <?php echo htmlspecialchars($currentUserFirstName); ?> 👋</div>
	<div class="topbar-right">
		<div class="badge" id="dashboardBadge"><?php echo htmlspecialchars($lowStockBadge); ?></div>
		<button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
	</div>
</div>
