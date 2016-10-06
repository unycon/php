<?php 
class SI_DateTime extends DateTime{
	private $setTime;
	private $year;
	private $month;
	private $day;
	private $lastDay;
	private $lastDate;
	private $firstDate;
	private $startNum;
	private $weekNum;
	

	function __construct($time = null, $object = null){
		parent::__construct($time, $object);
		
		$this->setTime = $time;
		$this->year = $this->format("Y");
		$this->month = $this->format("m");
		$this->day = $this->format("d");
		$this->hour = $this->format("H");
		$this->minute = $this->format("i");
		$this->second = $this->format("s");
	}
	/* GETTER */

	function year(){ return $this->year; }
	function month(){ return $this->month;}
	function day(){ return $this->day;}
	function hour(){ return $this->hour;}
	function minute(){ return $this->minute;}
	function second(){ return $this->second;}

	function get_Date(){ return $this->format("Y-m-d"); }

	
	function startNum(){
		echo $this->startNum;
		if($this->startNum) return $this->startNum;
		return ( date('w', mktime(0, 0, 0, $this->month, 1, $this->year)));
	}

	function lastNum(){
		//if($this->startNum) return $this->startNum;
		//return ( date('w', mktime(0, 0, 0, $this->month, 1, $this->year)) - 1);
	}
	function lastDay(){ 
		if($this->lastDay) return $this->lastDay;
		return $this->format("t");
		
	}

	function firstDate(){ 
		if($this->firstDay) return $this->firstDate;
		return $this->format("Y-m")."-1";
	}
	
	function lastDate(){ 
		if($this->lastDay) return $this->lastDate;
		return $this->format("Y-m-t");
		
	}

	function weekNum(){
		if($this->weekNum) return $this->weekNum;
		return $this->format("w");
	}

	function is_date(){ return date_create($this->setTime); }

	function weekText(){ return $this->get_week_text( $this->getTimezone()->getName() ); }

	function weekShotText(){ return $this->get_week_text( $this->getTimezone()->getName() , "shot"); }
	

	/**
	* 월 기준 날짜 계산
	*/
	function diffMonth($month, $is_last = null){
		
		if(!$month) return $this->get_date();
		if(!is_numeric($month)) return $this->get_date();
		if($month > 0 ) $month ="+".$month;
		$date = date("Y-m-d", strtotime($month." month", mktime(0, 0, 0, $this->month, 1, $this->year)) ); //한달전
		$date = new SI_DateTime($date);

		if(is_null($is_last) ){
			if($this->day >= $date->lastDay()){ $time = $date->year()."-".$date->month()."-".$date->lastDay(); }
			else{ $time = $date->year()."-".$date->month()."-".$this->day; }
			return $time;
		}

		if(is_bool($is_last) && $is_last ){ return $time = $date->year()."-".$date->month()."-".$date->lastDay(); }
		else{ return $time = $date->get_date(); }
		
		return $time;
	}

	/**
	* 일 기준 날짜 계산
	*/
	function diffDay($day){
		
		if(!$day) return $this->get_date();
		if(!is_numeric($day)) return $this->get_date();
		if($day > 0 ) $day ="+".$day;
		$date = date("Y-m-d", strtotime($day." day", mktime(0, 0, 0, $this->month, $this->day, $this->year)) ); //한달전
		$date = new SI_DateTime($date);
		return $time = $date->get_date();
	}

	function diffBetween($date) { return intval((strtotime($date) - strtotime($this->get_Date())) / 86400); }

	function get_week_text($timezone, $type="long"){

		$timetext = array(
			"Asia/Seoul"=> array(
				"long"	=> array("일요일", "월요일", "화요일","수요일","목요일","금요일","토요일"),
				"shot"	=> array("일","월", "화","수","목","금","토")
			),
			"Asia/Seoul" => array(
				"long"	=> array("Sunday", "Monday", "Tuesday","Wednesday","Thursday","Friday","Saturday"),
				"shot"	=> array("Sun", "Mon", "Tue","Wed","Thu","Fri","Sat")
			)
		);

		switch(strtolower($timezone) ){
			case "ko": case "asia/seoul" : case "seoul" :
				return $timetext["Asia/Seoul"][$type][$this->weekNum()];
				break;
			case 'en' : case 'america'			 :				case 'english'				:
			case 'ac' :	case 'america/Rio_branco': case 'al' :	case 'america/Maceio'		:
			case 'ap' :	case 'america/Belem'	 : case 'am' :	case 'america/Manaus'		:
			case 'ba' :	case 'america/Bahia'	 : case 'ce' :	case 'america/Fortaleza'	:
			case 'df' :	case 'america/Sao_Paulo' : case 'es' :	case 'america/Sao_Paulo'	:
			case 'go' :	case 'america/Sao_Paulo' : case 'ma' :	case 'america/Fortaleza'	:
			case 'mt' :	case 'america/Cuiaba'	 : case 'ms' :	case 'america/Campo_Grande'	:
			case 'mg' :	case 'america/Sao_Paulo' : case 'pr' :	case 'america/Sao_Paulo'	:
			case 'pb' :	case 'america/Fortaleza' : case 'pa' :	case 'america/Belem'		:
			case 'pe' :	case 'america/Recife'	 : case 'pi' :	case 'america/Fortaleza'	:
			case 'rj' :	case 'america/Sao_Paulo' : case 'rn' :	case 'america/Fortaleza'	:
			case 'rs' :	case 'america/Sao_Paulo' : case 'ro' :	case 'america/Porto_Velho'	:
			case 'rr' :	case 'america/Boa_Vista' : case 'sc' :	case 'america/Sao_Paulo'	:
			case 'se' :	case 'america/Maceio'	 : case 'sp' :	case 'america/Sao_Paulo'	:
			case 'to' :	case 'america/Araguaia'	 :
				return $timetext["America/New_York"][$type][$this->weekNum()];
				break;
			default : 
		}

		return false;

	}
}
?>