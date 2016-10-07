<!DOCTYPE html>
<html>
<head>
<title>Calender</title>

<link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php 
include_once("../DateTime/class.datetime.php");
include_once("./class.calender.php");

$calender = new SI_Calender(); ?>

<style>
	.box{width:300px; margin-right:20px; float:left;}
</style>

<div class="box"><?php echo $calender->calender(); ?></div>

<div class="box">
	<?php 
		$calender->set_title_format("Y년 d월");
		$calender->set_week_title(array("일","월","화","수","목","금","토"));

		echo $calender->calender(); 
	?>
</div>

</body>
</html>