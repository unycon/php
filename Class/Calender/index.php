<!DOCTYPE html>
<html>
<head>
<title>Calender</title>

<link rel="stylesheet" href="./css/style.css">
<script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="./js/calender.js"></script>

</head>
<body>

<?php 
include_once("../DateTime/class.datetime.php");
include_once("./class.calender.php");

$calender = new SI_Calender(); ?>

<style>
	.box{width:500px; padding:20px; float:left;}
</style>

<div class="box"><?php echo $calender->calender(); ?></div>

<div class="box">
	<?php 
		$calender->set_title_format("Y년 m월");
		$calender->set_week_title(array("일","월","화","수","목","금","토"));
		$calender->set_blank(true);
		$calender->set_prev_blank(true);
		$calender->set_next_blank(true);

		echo $calender->calender(); 
	?>
</div>

</body>
</html>