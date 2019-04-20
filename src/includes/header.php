<header class="masthead w-100 position-fixed">
	<div class="inner">
		<a href="index.php">
			<img href="index.php" id="logo" src="images/logo.png" class="position-absolute"></img>
		</a>
		<nav class="nav nav-masthead justify-content-center">
			<a class="nav-link <?=(PAGENAME == 'home' ? 'active' : '')?>" href="./"><span class="d-none d-md-block">Home</span><i class="d-md-none d-block" data-feather="home"></i></a>
			<?=(!MMS\Security::verSession() ? '' : '<a class="nav-link '.(PAGENAME == 'aboutme' ? 'active' : '').'" href="./aboutme.php"><span class="d-none d-md-block">Il mio profilo</span><i class="d-md-none d-block" data-feather="user"></i></a>')?>
			<?=(!MMS\Security::isAdmin() ? '' : '<a class="nav-link '.(PAGENAME == 'dashboard' ? 'active' : '').'" href="./dashboard.php"><span class="d-none d-md-block">Dashboard</span><i class="d-md-none d-block" data-feather="shield"></i></a>')?>
			<a class="nav-link <?=(PAGENAME == 'events' ? 'active' : '')?>" href="./events.php"><span class="d-none d-md-block">Eventi</span><i class="d-md-none d-block" data-feather="book-open"></i></a>
			<a class="nav-link <?=(PAGENAME == 'aboutus' ? 'active' : '')?>" href="./aboutus.php"><span class="d-none d-md-block">Contatti</span><i class="d-md-none d-block" data-feather="map-pin"></i></a>
			<?=(!MMS\Security::verSession() ? '' : '<a class="nav-link" href="./logout.php"><span class="d-none d-md-block">Esci</span><i class="d-md-none d-block" data-feather="log-out"></i></a>')?>
		</nav>
	</div>
</header>