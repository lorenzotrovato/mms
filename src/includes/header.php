<header class="masthead w-100 position-fixed">
	<div class="inner">
		<a href="index.php" id="logo" class="position-absolute"><h3 class="masthead-brand">M</h3></a>
		<nav class="nav nav-masthead justify-content-center">
			<a class="nav-link <?=(PAGENAME == 'home' ? 'active' : '')?>" href="./">Home</a>
			<?=(!MMS\Security::isAdmin() ? '' : '<a class="nav-link '.(PAGENAME == 'dashboard' ? 'active' : '').'" href="./dashboard.php">Dashboard</a>')?>
			<a class="nav-link <?=(PAGENAME == 'events' ? 'active' : '')?>" href="./events.php">Eventi</a>
			<a class="nav-link <?=(PAGENAME == 'aboutus' ? 'active' : '')?>" href="./aboutus.php">Contatti</a>
			<?=(!MMS\Security::verSession() ? '' : '<a class="nav-link" href="./logout.php">Esci</a>')?>
		</nav>
	</div>
</header>