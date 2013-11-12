<?php
	error_reporting(1);
	session_start();

	function conectar(){//Genera la conexión a la base de datos
		$con=mysqli_connect("localhost","root","","prepanet2");
		if (mysqli_connect_errno())
	  	{
	  		print "Falló la conexión a la base de datos: " . mysqli_connect_error();
	  	}
	  	mysqli_set_charset($con, "utf8");
	  	return $con;
	}
	
	//Valida los permisos del usuario para estar en una página, en caso de no tenerlos los envia al index si es que iniciaron sesión
	//En caso de no tener una sesión iniciada, esta se crea desde el index.
	function validarSession($session){
		if($session === "login"){
			if(isset($_SESSION["user"])){
				header("location: index.php");
			}
		}
		else if($session === "index"){
			if(!isset($_SESSION["user"])){
				if(!isset($_POST["user"])){
					header("location: login.php");
				}
				else{
					$user = $_POST["user"];
					$pass = md5($_POST["password"]);

					/*
					$stmt = mysqli_prepare($link, "SELECT * FROM Alumno where Matricula = ? AND Password = ?");
					mysqli_stmt_bind_param($stmt, 'ss', $_POST["user"], $pass);
					$code = 'DEU';
					$language = 'Bavarian';
					$official = "F";
					$percent = 11.2;
					// execute prepared statement 
					mysqli_stmt_execute($stmt);					
					*/
					$con = conectar();
					
					if($result = mysqli_query($con,"SELECT * FROM Alumno where Matricula = '$user' AND Password = '$pass'")){
						if(mysqli_num_rows($result) == 1){
							$row = $result->fetch_array(MYSQLI_ASSOC);
							$_SESSION["user"] = $row["Matricula"];
							$_SESSION["name"] = $row["Nombre"];
							$_SESSION["type"] = "student";
							mysqli_free_result($result);
							mysqli_close($link);
						}
						else{//El usuario no es alumno
							if($result = mysqli_query($con,"SELECT * FROM Administrador where Nomina = '$user' AND Password = '$pass'")){
								if(mysqli_num_rows($result) == 1){
									$row = $result->fetch_array(MYSQLI_ASSOC);
									$_SESSION["user"] = $row["Nomina"];
									$_SESSION["name"] = $row["Nombre"];
									$_SESSION["type"] = "admin";
									mysqli_free_result($result);
									mysqli_close($link);
								}
								else{
									header("Location: login.php?error=1");//El usuario no está en la base de datos
								}
							}
							else{
								mysqli_close($con);

								header("Location: login.php?error=2");///No se pudo la consulta en la base de datos para administradores
							}
						}
					}
					else{
						mysqli_close($con);
						header("Location: login.php?error=2");//No se pudo la consulta en la base de datos para alumnos
					}
				}
			}
		}
		else if($session === "student"){//Falta validar que sea una sesión de estudiante
			if(!isset($_SESSION["user"])){
				//header("location: logout.php");
				header("location: login.php?error=1");
			}
			else{
				if($_SESSION["type"] !== "student"){
					header("location: index.php?error=1");
				}
			}
		}
		else if($session === "admin"){//Falta validar que sea una sesión de administrador
			if(!isset($_SESSION["user"])){
				//header("location: logout.php");
				header("location: login.php?error=1");
			}
			else{
				if($_SESSION["type"] !== "admin"){
					header("location: index.php?error=1");
				}	
			}
		}
	}

	function getDatosRegistro(){//Regresa datos para el formulario del registro administrativo
		$con = conectar();
		if($result = mysqli_query($con,"SELECT * FROM Alumno where Matricula = '".$_SESSION["user"]."';")){
			if(mysqli_num_rows($result) === 1){
				mysqli_close($link);
				return $result->fetch_array(MYSQLI_ASSOC);
			}
		}
	}

	function setRegistro(){
		$con = conectar();
		$telefono = $_GET["phone"];
		$correo = $_GET["email"];
		//$Nmaterias = $_GET["num"];
		$incubadora = $_GET["incubadora"];
		$query = "UPDATE Alumno SET Telefono = $telefono, Mail = '$correo', Incubadora = '$incubadora' WHERE  Matricula = '".$_SESSION["user"]."';";
		mysqli_query($con, $query);
		
		mysql_close($con);
	}

	function getCursables(){
		$con = conectar();
		$query = "SELECT * FROM PlanEstudios NATURAL JOIN (SELECT Clave, Nombre FROM Materia WHERE Clave NOT IN (SELECT Clave FROM Cursadas)) A;";
		//$query = "SELECT Clave, Nombre, Unidades, Cuatrimestre FROM Materia NATURAL JOIN PlanEStudios order by Cuatrimestre asc;";
		if($result = mysqli_query($con, $query)){
			return $result;
		}
	}

	function setInscripcion(){
		$con = conectar();
		$materias = $_POST["materias"];
		for($i = 0; $i < count($materias); $i++){
			if(mysqli_query($con,"INSERT INTO Cursadas VALUES ('".$_SESSION["user"]."', '$materias[$i]', 'DIC2013');")){
				print "Si";
			}
			else{
				print "No";
			}
		}
		
	}

	function getReporteInscritas(){
		
	}

	function setNewPassword(){
		$con = conectar();
		$password = md5($_POST['oldPassword']);
		$newPassword = md5($_POST["newPassword"]);
		$newPassword2 = md5($_POST["newPassword2"]);
		if($newPassword == $newPassword2){
			$result = mysqli_query($con,"SELECT Password FROM Alumno where Matricula = '".$_SESSION["user"]."';");
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if($row["Password"] == $password){
				$result = mysqli_query($con, "UPDATE Alumno SET Password = '$newPassword' WHERE Matricula = '".$_SESSION["user"]."';");
				
			}
		}

		mysql_close($con);
	}

?>