<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Timedate extends MX_Controller
{

    function __construct() {
    parent::__construct();
    }

    function get_nice_date($timestamp,$format){
        switch ($format) {
            case 'full':
                //FULL: Friday 18th of February 2018 at 10:00:00 AM
                $date = date('l jS \of FY \a\t h:i:s A',$timestamp);
                break;
            case 'cool':
                //FULL: Friday 18th of February 2018
                $date = date('l jS \of F Y',$timestamp);
                break;
            case 'shorter':
                //SHORTER: 18th of February 2018
                $date = date('jS \of F Y',$timestamp);
                break;
            case 'mini':
                //MINI: 18th Feb 2018
                $date = date('jS M Y',$timestamp);
                break;
            case 'oldschool':
                //OLDSCHOOL: Friday 18/2/18
                $date = date('j\/n\/y',$timestamp);
                break;
            case 'datepicker':
                //DATEPICKER: 18-2-2018
                $date = date('d\-m\-Y',$timestamp);
                break;
            case 'datepicker_us':
                //DATEPICKER: 18-2-2018
                $date = date('m\/d\/Y',$timestamp);
                break;
            case 'monyear':
                //MONYEAR: Mon 2018
                $date = date('F Y',$timestamp);
                break;
        }

        return $date;
    }

    function make_timestamp_from_datepicker($datepicker){
        $hour = 7;
        $minute = 0;
        $second = 0;
        
        $day = substr($datepicker, 0, 2);
        $month = substr($datepicker, 3, 2);
        $year = substr($datepicker, 6, 4);
        
        $timestamp = mktime($hour,$minute,$second,$month,$day,$year);
        return $timestamp;
    }
    function make_timestamp_from_datepicker_us($datepicker){
        $hour = 7;
        $minute = 0;
        $second = 0;
        
        $month = substr($datepicker, 0, 2);
        $day = substr($datepicker, 3, 2);
        $year = substr($datepicker, 6, 4);
        
        $timestamp = mktime($hour,$minute,$second,$month,$day,$year);
        return $timestamp;
    }
    
    function make_timestamp($day,$month,$year){
        $hour = 7;
        $minute = 0;
        $second = 0;
        
        $day = substr($datepicker, 0, 2);
        $month = substr($datepicker, 3, 2);
        $year = substr($datepicker, 6, 4);
        
        $timestamp = mktime($hour,$minute,$second,$month,$day,$year);
        return $timestamp;
    }
    
}