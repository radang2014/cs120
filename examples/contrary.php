<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Demo</title>
</head>

<body>
	<?php
		$word = $_GET["w"];
		if ($word == "black")
			echo "White";
		else if ($word == "white")
			echo "Black";
		else if ($word == "up")
			echo "Down";
		else if ($word == "down")
			echo "Up";
		else if ($word == "yes")
			echo "No";
		else if ($word == "no")
			echo "Yes";
		else echo "nothing to say!";
	?>
</body>
</html>