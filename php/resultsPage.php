<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<title>Website Title</title>
	<link rel="stylesheet" href="css/index.css">

</head>
<body>

	<div>
		<?php
			echo "<h1>This is the results page</h1>";
			if(isset($_POST["TwitterHandle"])){
				echo "<h1>Twitter Handle ". $_POST["TwitterHandle"]."</h1>";
			}
			if(isset($_POST["Keyword"])){
				echo "<h1>Keyword ". $_POST["Keyword"]."</h1>";
			}
		?>	
	</div>
	
</body>
</html>