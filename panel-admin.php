<?php
session_start();

if(isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {

    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Panel-Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">


</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container-fluid"><br><br><br><br><br>
        <h3>Bienvenido <span class="username"> Doctor <?php echo $username; ?></span></h3>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="panel panel-white no-radius text-center">
                                <div class="panel-body">
                                    <a href="pacientes.php" class="texto-1" onclick="clickDiv('#list-home-list')">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-user-doctor fa-stack-1x fa-inverse fa-3x"></i>
                                        </span>
                                        <h4 class="StepTitle">Pacientes</h4>
                                        <p>Ver Pacientes</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-white no-radius text-center">
                                <div class="panel-body">
                                    <a href="citas.php" class="texto-1" onclick="clickDiv('#list-pat-list')">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-clipboard-user fa-stack-1x fa-inverse fa-3x"></i>
                                        </span>
                                        <h4 class="StepTitle">Citas</h4>
                                        <p>Ver Citas</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-white no-radius text-center">
                                <div class="panel-body">
                                    <a href="recetas.php" class="texto-1" onclick="clickDiv('#list-pres-list')">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-notes-medical fa-stack-1x fa-inverse fa-3x"></i>
                                        </span>
                                        <h4 class="StepTitle">Recetas</h4>
                                        <p>Ver Recetas</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-white no-radius text-center">
                                <div class="panel-body">
                                    <a href="seguimiento.php" class="texto-1" onclick="clickDiv('#list-seg-list')">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-calendar-alt fa-stack-1x fa-inverse fa-3x"></i>
                                        </span>
                                        <h4 class="StepTitle">Seguimientos</h4>
                                        <p>Ver Seguimientos</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
</body>

</html>