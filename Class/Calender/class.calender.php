<?php

class SI_Calender{
	private $from_date;
	private $to_date;
	private $next_date;
	private $prev_date;

	private $week_title = array("Sun", "Mon", "Tue","Wed","Thu","Fri","Sat");
	private $title_format = "Y-m";

	private $prev_btn;
	private $next_btn;
	private $blank_prev_date = false;
	private $blank_next_date = false;
	private $abs_row = true;



	var $cur_url;
	var $doing_ajax;

	function __construct($from_date = null, $to_date = null){

		if($this->is_date($from_date)){ $this->from_date = $from_date; }

		$this->from_date = $from_date;

		$this->prev_btn = "<<";
		$this->next_btn = ">>";

	}

	function get_week_title(){ return $this->week_title; }

	function set_blank($bool = false){ $this->blank_prev_date = $this->blank_next_date = $bool; }
	function set_prev_blank($bool = false){ $this->blank_prev_date = $bool; }
	function set_next_blank($bool = false){ $this->blank_next_date = $bool; }
	function absolute_row($bool = true){$this->abs_row = $bool;}

	

	function set_title_format($format){ $this->title_format = $format; }

	function set_week_title($array = null){ if(!$array) return; $this->week_title = array_merge($array, $this->week_title); }

	function is_date($date){return date_create($date);}
	
	function calender($date = null){
		$today	= new SI_DateTime();
		$from_date = $date ? $date : $this->from_date;
		if(!$from_date) $from_date = $today->get_date();

		$change = new SI_DateTime($from_date);
		

		$from_year			= $change->year();
		$from_month			= $change->month();
		$from_day			= $change->day();
		$last_day			= $change->lastDay();

		$prev_date = new SI_DateTime( $change->diffMonth(-1) );
		
		$next_date = new SI_DateTime( $change->diffMonth(1) );
		
		$first_date = new SI_DateTime( $change->firstDate() );
		$date_diff = $change->diffBetween($first_date->get_date());

		$week_cnt = strftime("%U",mktime(0,0,0,$change->month(),$change->lastDay(),$change->year()))-strftime("%U",mktime(0,0,0,$change->month(), 1,$change->year())) + 1;

		// 이전달
		$prev_btn  = "<p id='btn_prev_wrap' class='btn_prev_wrap".($this->doing_ajax ? " do_ajax" : "")."' data-year='{$prev_date->year()}' data-month='{$prev_date->month()}' data-day='{$prev_date->day()}' >";
		$prev_btn .=	"<a href='#btn_reserve_prev_wrap'>";
		$prev_btn .=		"<span class='btn_cal_prev'><i class='icon-left-open-outline'></i>{$this->prev_btn}</span>";
		$prev_btn .=	"</a>&nbsp;";
		$prev_btn .= "</p>";

		//다음달
		$next_btn  = "<p id='btn_next_wrap' class='btn_next_wrap".($this->doing_ajax ? " do_ajax" : "")."' data-year='{$next_date->year()}' data-month='{$next_date->month()}' data-day='{$next_date->day()}'>";
		$next_btn .=	"<a href='#btn_reserve_next_wrap'>";
		$next_btn .=		"<span class='btn_cal_next'>{$this->next_btn}<i class='icon-right-open-outline'></i></span>";
		$next_btn .=	"</a>";
		$next_btn .= "</p>";

		// 1일 이전 빈칸
		$prev_blank_day = "<tr class='date'>";
		for ($i = 0; $i < $startnum =$first_date->startNum(); $i++) { 
			$minus_weektext = "";
			$minus_day = "<p class='daynum'>&nbsp;</p>";
			if($this->blank_prev_date){
				$minus_date		= new Si_DateTime( $first_date->diffDay( -1 * ( $startnum - $i) ) ) ;
				$minus_day		= "<p class='daynum'>{$minus_date->day()}</p>";
				$minus_weektext = " ".strtolower( $minus_date->weekShotText() );
			}
			$prev_blank_day .=				"<td class='mini blank{$minus_weektext}'>{$minus_day}</td>".PHP_EOL; 
		}

		$last_blank_day = "";
		$last_date = new Si_DateTime( $change->lastDate() );
		$lastweekNum =  $last_date->weekNum();

		//if( ($day + 1) ==  $change->lastDay()){$last_weekNum = (6 - $vDate->weekNum());} // end loof

		// 마지막일 이후 빈칸
		$plus_num = 0;
		if($lastweekNum < 6){
			$plus_weektext = ""; $plus_day = "<p class='daynum'>&nbsp;</p>";
			for ($i = 0; $i < ( 6 - $lastweekNum ); $i++) {
				if($this->blank_next_date){
					$plus_date		= new Si_DateTime( $last_date->diffDay( $i +1 )  ) ;
					$plus_num		= sprintf("%d", $plus_date->day());
					$plus_day		= "<p class='daynum'>".$plus_num."</p>";
					$plus_weektext	= " ".strtolower( $plus_date->weekShotText() );
				}
				$last_blank_day .= "<td class='mini blank{$plus_weektext}'>{$plus_day}</td>".PHP_EOL; 
			}
			$last_blank_day .= "</tr>".PHP_EOL;
		}
		
		// 6주 고정 (크기 동일)
		if($this->abs_row && $week_cnt < 6){
			$last_blank_day .= "<tr class='date'>";
			$plus_weektext = ""; $plus_day = "<p class='daynum'>&nbsp;</p>";
			$diff_week_cnt = 6 - $week_cnt;
			
			for ($i = 1; $i <= ($diff_week_cnt * 7); $i++) {
				$plus_date		= new Si_DateTime( $last_date->diffDay($i + $plus_num)  ) ;
				if($this->blank_next_date){
					$plus_day		= "<p class='daynum'>".sprintf("%d", $plus_date->day())."</p>";
					$plus_weektext	= " ".strtolower( $plus_date->weekShotText() );
				}
				
				$last_blank_day .= "<td class='mini blank{$plus_weektext}'>{$plus_day}</td>".PHP_EOL; 
				if($plus_date->weekNum() == 6 )  $last_blank_day .="</tr><tr class='date'>".PHP_EOL;;
			}
			$last_blank_day .= "</tr>";
		}
		
	
		// 일 추가
		$days_html = "";
		for ($day = 0; $day < $change->lastDay(); $day++) {
			$vDate = new SI_DateTime( $change->diffDay($date_diff + $day) );

			$today_diff = $today->diffbetween($vDate->get_date());
			$form_diff = $today->diffbetween($change->get_date());

			$weektext = $vDate->weekText();
			
			
			$day_info = array(
				"is_wrap" => false, 
				"text" => "", 
				"class" => array(strtolower( substr($weektext, 0 ,3) ))
			);


			if($today_diff > 0){ $day_info["class"][] ="act";}
			else if($today_diff < 0){ $day_info["class"][] ="none_act";}
			else if($today_diff == 0){ $day_info["class"][] ="today";}
			
			if($form_diff == 0){ $day_info["class"][] ="current";}

			// 날짜 세팅
			$td_html  = "<td id='mini_{$day}'class='mini ".implode(" " , $day_info['class'])."' data-date='{$vDate->get_date()}' data-year='{$vDate->year()}' data-month='{$vDate->month()}' data-day='{$vDate->day()}'>".PHP_EOL;
 
			//$check_day = wz_holiday_check($vDate->get_date()); // 휴무일 검사 및 날짜 표시

			if($day_info['is_wrap']){
				
				$sel_param = array("sel_year" => $select_year, "sel_month" => $select_month,"sel_day"=> $day); 
				$param = array();
				foreach($sel_param as $sel_key => $sel_val){$param[] = $sel_key."=".$sel_val;}
				$param =  implode("&", $param);
				$td_html .= "<a href='".$this->get_reservation_url()."&".$param."#mini_{$day}'>";
				
			}
			$check_day = null;
			$td_html .= "<div style='height:100%;'>";
			$td_html .= "<p class='daynum'>".($check_day ? '<span class="hlday">'.$check_day.'</span>' : sprintf("%d", $vDate->day()))."</p>";
			$td_html .= "<p class='state_text'>".$day_info['text']."</p>";
			$td_html .= "</div>";
			if($day_info['is_wrap']){$td_html .= "</a>";}
			$td_html .= "</td>".PHP_EOL;
			$days_html .=  $td_html;

			
			if($vDate->weekNum() == 6 ) { // When Saturday
				$days_html .= "</tr>".PHP_EOL;
				if(($day + 1) !=  $change->lastDay()) { $days_html .= "<tr class='date'>".PHP_EOL; } // loof
			}
			
			if( ($day + 1) ==  $change->lastDay()){$last_weekNum = (6 - $vDate->weekNum());} // end loof
		}

	


$html  = "<div class='cal_wrapper'>";
$html .=	"<div class='cal_navi'>";
$html .=		"<span class='cal_navi_date title'>".$change->format($this->title_format)."</span>";
$html .=		$prev_btn;
$html .=		$next_btn;
$html .=	"</div>";
			// --- Week Text
$html .=	"<div class='cal_week_wrapper'>";
$html .=		"<table border='0' cellpadding='0' cellspacing='0' class='caltable cal_week'>";
$html .=			"<tbody>".$this->cal_week_list($this->week_title)."</tbody>";
$html .=		"</table>";
$html .=	"</div>";
			// --- Week Days
$html .=	"<div class='cal_days_wrapper'>";
$html .=	"<table border='0' cellpadding='0' cellspacing='0' class='caltable cal_days'>";
$html .=		"<tbody>";
$html .=			$prev_blank_day;
$html .=			$days_html;
$html .=			$last_blank_day;
		

		

		$html .= "</tbody></table></div></div>";
		return $html;
	}

	function get_list_url(){
		if($this->list_url) return $this->list_url;
		else $this->cur_url."list/".$_REQUEST['rv_type'].($_REQUEST['rv_type2'] ? "/".$_REQUEST['rv_type2'].($_REQUEST['rv_idx'] ? "/".$_REQUEST['rv_idx']: '') : '');
	}
	function get_view_url(){
		if($this->view_url) return $this->view_url;
		else $this->cur_url."view/".$_REQUEST['rv_type'].($_REQUEST['rv_type2'] ? "/".$_REQUEST['rv_type2'].($_REQUEST['rv_idx'] ? "/".$_REQUEST['rv_idx']: '') : '');
	
	}
	function get_reservation_url(){
		if($this->reservation_url) return $this->reservation_url;
		else $this->cur_url."reservation/".$_REQUEST['rv_type'].($_REQUEST['rv_type2'] ? "/".$_REQUEST['rv_type2'].($_REQUEST['rv_idx'] ? "/".$_REQUEST['rv_idx']: '') : '');
	
	}

	

	function cal_week_list($date_array = array()){
		if($date_array){ array_merge($date_array, $this->week_title); }
		else{ $date_array = $this->week_title; }
		
		$date_tr  ="<tr class='date_list'>";
		$date_tr .=	"<th class='sun'>%s</th>";
		$date_tr .=	"<th class='mon'>%s</th>";
		$date_tr .=	"<th class='tue'>%s</th>";
		$date_tr .=	"<th class='wed'>%s</th>";
		$date_tr .=	"<th class='thu'>%s</th>";
		$date_tr .=	"<th class='fri'>%s</th>";
		$date_tr .=	"<th class='sat'>%s</th>";
		$date_tr .="</tr>";
		$date_tr = preg_replace_callback("/\%s/", function($matches) use (&$date_array) {
			return array_shift($date_array);
		}, $date_tr);

		return $date_tr;
	}
}
?>