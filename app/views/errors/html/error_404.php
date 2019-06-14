<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<title>404 Page Not Found</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />


	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="/assets/css/style.min.css" rel="stylesheet" />	
	<!-- ================== END BASE CSS STYLE ================== -->
</head>

<body class="pace-top">
	<div id="page-container">
		<div class="error">
			<div class="error-code m-b-10">404 <i class="fa fa-warning"></i></div>
			<div class="error-content">
				<div class="error-message">Page Not Found</div>
				<div class="error-desc m-b-20">Al parecer se ha cerrado su sesión o la página que busca no está disponible</div>
				<div>
					<a href="/" class="btn btn-success">Regresar</a>
				</div>
			</div>
		</div>
	</div>
</body>

</html>