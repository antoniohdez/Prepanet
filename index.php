<?php
	include "DOMElements/view.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>
        PrepaNet - Inscripciones
    </title>
	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php
		printTopbar();
	?>
	<div class="container CScontenedor">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="content">
					<div class="centerText">
						<h3>Bienvenido al sistema de inscrpciones de PrepaNet</h3>
					</div>
					<div class="">
						Este sistema de inscripciones te permitirá llevar a cabo el proceso de unscripción de materias para tu próximo semestre de una forma fácil y ...
						<br/>
						Si no has utilizado este sistema antes, puedes ver una pequeña guia dando <a href="#">click aquí</a>.
					</div>
					<div class="centerText">
						<button class="btn btn-primary signIn" type="submit">Comenzar inscripción</button>
					</div>
				</div>
    		</div>
    	</div>
	</div>

	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
</body>
</html>