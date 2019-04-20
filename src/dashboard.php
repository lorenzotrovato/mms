<?php
    namespace MMS;
    require_once './includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
    define('PAGENAME', 'dashboard');
    
?>
<!doctype html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <title>Musetek - Innovation of the past</title>
    
        <!-- Bootstrap core CSS -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <link href="./css/style.css" rel="stylesheet">
    
        <!-- Custom styles for this template -->
        <link href="./css/dashboard.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="./favicon.png"/>
    </head>
    
    <body>
        <div class="navbar navbar-dark sticky-top flex-md-nowrap p-3">   
            <?php
                include './includes/header.php';
            ?>
        </div>
        <div class="container-fluid">
            <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle" style="margin-top: 15px; line-height: 30px; height: 40px; width: 40px; position: relative; transition: all 0.4s linear; left: 15px; z-index: 999"><span data-feather="arrow-right" style="width: 22px; height: 22px; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%); position: absolute;"></span></a>
            <div id="overlay" class="d-md-none" style="position: absolute; height: 100%; width: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 99; top: 0; left: 0; display: none"></div>
            <div class="row">
                <nav id="wrapper" class="col-lg-2 col-md-3 col-sm-4 col-6 bg-light sidebar">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="sb-link nav-link active" href="dash">
                                    <span data-feather="home"></span> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="finances">
                                    <span data-feather="dollar-sign"></span> Finanze
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="tickets">
                                    <span data-feather="shopping-cart"></span> Biglietti
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="visit">
                                    <span data-feather="eye"></span> Visita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="expos">
                                    <span data-feather="list"></span> Esposizioni
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="accessories">
                                    <span data-feather="briefcase"></span> Accessori
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="categories">
                                    <span data-feather="users"></span> Categorie
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="users">
                                    <span data-feather="user"></span> Utenti
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="sb-link nav-link" href="ticketval">
                                    <span data-feather="check-circle"></span> Validazione biglietti
                                </a>
                            </li>
                        </ul>
    
                        <!--<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Saved reports</span>
                            <a class="d-flex align-items-center text-muted" href="#">
                                <span data-feather="plus-circle"></span>
                            </a>
                        </h6>
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span> Current month
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span> Last quarter
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span> Social engagement
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text"></span> Year-end sale
                                </a>
                            </li>
                        </ul>-->
                    </div>
                </nav>
                <main id="mainsection" role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                </main>
            </div>
        </div>
    
        <!-- Bootstrap core JavaScript
    ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="./js/jquery-3.3.1.min.js"></script>
        <script>
            window.jQuery || document.write('<script src="./js/jquery-3.3.1.min.js"><\/script>')
        </script>
        <script src="./js/bootstrap.bundle.min.js"></script>
    
        <!-- Icons -->
        <script src="./js/feather.min.js"></script>
        <script>
            feather.replace();
        </script>
    
        
        
        <script src="./js/adminpanel.js"></script>
    </body>

</html>