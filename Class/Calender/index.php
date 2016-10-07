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

$calender = new SI_Calender(); 
$calender->set_title_format("Y년 m월");
$calender->set_week_title(array("일","월","화","수","목","금","토"));
$calender->set_blank(false);
	//$calender->set_prev_blank(true);
	//$calender->set_next_blank(true);
$calender->absolute_row(true);
?>

<style>
	body{width:1280px; margin:0 auto;}
	.box{width:30%; padding:20px; float:left;}
</style>

<?php 
	$view_year = new Si_DateTime("2015-01-01");
	

	for($i = 1; $i <= 12; $i++){ 
		$diff_year =  $i  - $view_year->month();
		$view_date = new Si_DateTime( $view_year->diffMonth($diff_year) );
	?>
		<div class="box"><?php echo $calender->calender($view_date->get_date()); ?></div>
	<?php }
?>
</body>
</html>