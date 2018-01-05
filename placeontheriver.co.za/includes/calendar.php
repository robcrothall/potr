<?php

    /**
     * Module name:	calendar.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Calendar class.
     */
		/**  Original code:
		*@author  Xu Ding
		*@email   thedilab@gmail.com
		*@website http://www.StarTutorial.com
		**/
class Calendar {  
     
    /**
     * Constructor
     */
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    /********************* PROPERTY ********************/  
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;
    
     
    /********************* PUBLIC **********************/  
        
    /**
    * print out the calendar
    */
    public function show() {
        $year  = date("Y",time());
        if (isset($_GET["year"])) {
			$year = $_GET["year"];        
        }
        if (null==$_SESSION["year"]) {
        	$_SESSION["year"] = $year;
        } else {
        	$year = $_SESSION["year"];
        }

        $month = date("m",time());
        if (isset($_GET["month"])) {
			$month = $_GET["month"];        
        }
        if (null==$_SESSION["month"]) {
        	$_SESSION["month"] = $month;
        } else {
        	$month = $_SESSION["month"];
        }
         
        $this->currentYear=$year;
        $this->currentMonth=$month;
        $this->daysInMonth=$this->_daysInMonth($month,$year);  
        $content='<div id="calendar">'. "\r\n" . 
                 '<div class="box">'. "\r\n" . 
                 $this->_createNavi(). "\r\n" . 
                 '</div>'. "\r\n" . 
                 '<div class="box-content">'. "\r\n" . 
                 '<ul class="label">'.$this->_createLabels().'</ul>' . "\r\n";   
        $content.='<div class="clear"></div>' . "\r\n";     
        $content.='<ul class="dates">' . "\r\n";    
        $weeksInMonth = $this->_weeksInMonth($month,$year);
        // Create weeks in a month
        for( $i=0; $i<$weeksInMonth; $i++ ){
        		//Create days in a week
            for($j=1;$j<=7;$j++){
            	$content.=$this->_showDay($i*7+$j);
            }
        }
        $content.='</ul>' . "\r\n";
        $content.='<div class="clear"></div>' . "\r\n";
        $content.='</div>' . "\r\n";
        $content.='</div>' . "\r\n";
        return $content;   
    }
     
    /********************* PRIVATE **********************/ 
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber){
        if($this->currentDay==0){
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                $this->currentDay=1;
            }
        }
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
            $cellContent = $this->currentDay;
            $this->currentDay++;   
        }else{
            $this->currentDate =null;
            $cellContent=null;
        }
        /*------------------------------
        return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
                ($cellContent==null?'mask':'').'">'.$cellContent.'</li>';
        --------------------------------*/
        $today_day = date("d");
        $today_mon = date("m");
        $today_yea = date("Y");
        $class_day = ($cellContent == $today_day 
        					&& $this->currentMonth == $today_mon 
        					&& $this->currentYear == $today_yea ? "calendar_today" : "calendar_days");
        $rows = query("SELECT * FROM bookings WHERE room_date = ?", $this->currentDate);
        if (count($rows) == 1)
        {
            $status = $rows[0]["room_status"];
            if ($status > 0) {
            	$class_day = "calendar_booked";
            }
        }
		  if(isset($_SESSION["id"])) {
			return '<li id="' . $this->currentDate . '" class="' . $class_day . '" onClick="clked(this.id)">' . $cellContent . '</li>' . "\r\n";
		  } else {
			return '<li class="' . $class_day . '">' . $cellContent . '</li>' . "\r\n";
		  }
		/*
        return '<li id="' . $this->currentDate . '" class="' . $class_day . '" onClick="clked(this.id)">' . $cellContent . '</li>' . "\r\n";
		*/    
    }
     
    /**
    * create navigation
    */
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
         
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
         
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
         
        return
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'"></a>'.
                    '<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'"></a>'.
            '</div>';
    }
         
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $content;
    }
     
     
     
    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
     
}
