<?php
	include "../view.php";
	validarSession("student");
	if($_SESSION["etapa"] == 3){
		header("location: inscripcionCompleta.php");
	}else{
		setRegistro();
	}


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
	<link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/inscripcion.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="../js/html5shiv.js"></script>
		<script src="../js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php
		printTopbar();
	?>
	<div class="container CScontenedor">
		<div class="row">
			<div class="col-md-3">
				<div class="content">
					<?php
						printProgress();
					?>
				</div>
				<div class="content">
					<div class="centerText">
						<span>Has inscrito <b><span id="unidades">0</span> unidades</b> de las 45 unidades máximas<br><br></span>
						<button id="inscribirMaterias" style="font-size:20px" class="btn btn-primary signIn" >Registrar materias</button>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="content">
					<div class="centerText">
						<h3>Selección de materias</h3>
					</div>
					<div class="contenedorMaterias">
						<div class="row">
							<div class="col-md-6">
								<div class="centerText title">
									<h4>Inscritas</h4>
								</div>
								<div id="inscritas" class="listaMateriasInscritas">
									<!--
									Espacio donde se visualizan las materias inscritas
									-->
								</div>
							</div>
							<div class="col-md-6" style="border-left:1px solid #CCC;">
								<div class="centerText title">
									<h4>Disponibles</h4>
								</div>
								<div id="disponibles" class="listaMaterias">
									<?php
										printCursables();
									?>
								</div>
							</div>
						</div>	
					</div>
				</div>
    		</div>
    	</div>
	</div>
	<?php
		printFooter();
	?>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
	//Script para hacer control de las materias seleccionadas para inscripción
	$(document).ready(function(){
		
	    var materias = 0;
	    
	    var unidades = 0;
	    $("#inscribirMaterias").attr("disabled","disabled");
	    $("#inscribirMaterias").html("Faltan " + (2-materias) + " materias");
	    var listaMaterias = [];

	    $("#inscribirMaterias").click(function(){
	    	var parametros = {
				"materias" : listaMaterias
			}
			$.ajax({
	            data: parametros,
	            url: 'addInscripcion.php',
	            type: 'post',
	            beforeSend: function () {
	                $("#inscribirMaterias").html("Registrando materias...");
	            },
	            success:  function (response) {
	                console.log(response);
	                window.location.replace("inscripcionCompleta.php");
	            }
	        });
	    });

		$("button").click(function(){
			//A partir del boton donde se dió click se obtiene el elemento padre que se va a mover.
	    	var $old = $(this).parent().parent().parent();
	    	if($old.parent().attr('id') === "disponibles" || $old.parent().attr('id') === "inscritas"){
		    	var width = $old.width();
		    	//var $old = $(this).parent().parent().parent();
		    	//console.log(unidades + parseInt($old.children().attr("unidades")));
		    	if($old.parent().attr('id') === "disponibles"){
		    		if((unidades + parseInt($old.children().attr("unidades"))) > 45){
		    			alert("No puedes inscribir más de 45 unidades");
		    			return;
		    		}
		    		else{
		    			materias++;
		    		
			    		unidades = parseInt(unidades) + parseInt($old.children().attr("unidades"));
			    		$("#unidades").html(unidades);
				    	listaMaterias.push($old.children().attr('id'));
				    	console.log(listaMaterias);

				    	$(this).removeClass("btn-success").addClass("btn-danger");
				    	$(this).html("Eliminar");
				    	$(this).attr('id', 'inscrita');
				    	if(materias >= 6){
							$(".btn-success").attr("disabled","disabled");
						}
				    	if(materias >= 2){
							$("#inscribirMaterias").html("Inscribir materias");
							$("#inscribirMaterias").removeAttr("disabled");
						}
						if(materias == 1){
							$("#inscribirMaterias").html("Falta " + materias + " materia");
						}
						if(materias == 0){
							$("#inscribirMaterias").html("Faltan " + (2-materias) + " materias");
						}
						//First we copy the arrow to the new table cell and get the offset to the document
						var $new = $old.clone(true);
						$new.prependTo('#inscritas');
						$(this).removeClass("btn-danger").addClass("btn-success");
				    	$(this).html("Inscribir");
				    	$(this).attr('id', 'disponible');
		    		}
				}else{
					materias--;

					unidades = parseInt(unidades) - parseInt($old.children().attr("unidades"));
					$("#unidades").html(unidades);
					
					var removeItem = $old.children().attr('id');

					var index = listaMaterias.indexOf(removeItem);
					if (index > -1) {
					    listaMaterias.splice(index, 1);
					}

					console.log(listaMaterias);
					if(materias < 6){
						$(".btn-success").removeAttr("disabled");
						
					}
					if(materias < 2){
						$(".btn-success").removeAttr("disabled");
						$("#inscribirMaterias").attr("disabled","disabled");
    					$("#inscribirMaterias").html("Faltan " + (2-materias) + " materias");
					}
					if(materias == 1){
						$("#inscribirMaterias").attr("disabled","disabled");
    					$("#inscribirMaterias").html("Falta " + materias + " materia");	
					}
					$(this).removeClass("btn-danger").addClass("btn-success");
			    	$(this).html("Inscribir");
			    	$(this).attr('id', 'disponible');
					//First we copy the arrow to the new table cell and get the offset to the document
					var $new = $old.clone(true);
					$new.prependTo('#disponibles');
					$(this).removeClass("btn-success").addClass("btn-danger");
			    	$(this).html("Eliminar");
			    	$(this).attr('id', 'inscrita');
				}
				//addthis.button(this);
				//var newOffset = $new.offset();
				//Get the old position relative to document
				//var oldOffset = $old.offset();
				//we also clone old to the document for the animation
				//var $temp = $old.clone().appendTo('body');
				//hide new and old and move $temp to position
				//also big z-index, make sure to edit this to something that works with the page
				/*
				$temp
				  .css('position', 'absolute')
				  .css('left', oldOffset.left)
				  .css('top', oldOffset.top)
				  .css('width', width)
				  .css('zIndex', 100);
				$new.hide();
				*/
				//$old.hide();
				//$old.css('visibility','hidden');//Esconde el viejo elemento pero mantiene el espacio vacio hasta terminar la animación
				var altura = $old.css("height");
				if($old.parent().attr('id') === "disponibles"){
					//$new.css('height', 0);
					$new.height(0);
					$new.animate({'height': altura}, 200, function(){
						$old.remove();
					});
					$old.animate({'height': 0}, 200, function(){
						
					});

				}
				else if($old.parent().attr('id') === "inscritas"){
					//$new.css('height', 0);
					$new.height(0);
					$new.animate({'height': altura}, 200, function(){
						$old.remove();
					});
					$old.animate({'height': 0}, 200, function(){
						
					});
				}
				//animate the $temp to the position of the new img
				/*
				$temp.animate( {'top': newOffset.top, 'left':newOffset.left}, 400, function(){
				   //callback function, we remove $old and $temp and show $new
				   $new.show();
				   $old.remove();
				   $temp.remove();
				});
				*/
			}
	    });
	});
    </script> 
</body>
</html>
