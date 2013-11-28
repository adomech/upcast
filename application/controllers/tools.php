<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

	
	public function index()
	{
			
			$this->load->helper('csv'); //Add library csv

			$data = array(array('Month', 'Mid Month Meeting Date','End of Month Testing Date'));
			
			$start  = DateTime::createFromFormat('d', '14');
			$period = new DatePeriod($start, new DateInterval('P1M'), 6, DatePeriod::EXCLUDE_START_DATE);
			
			
			$tmp_meeting=array();
			$tmp_test=array();
			$tmp_month=array();

			foreach($period as $day){

			  if(($day->format('D')=="Sat")||($day->format('D')=="Sun")){		
			  	
			  		$day->modify("next Monday");
			  	
			  		array_push($tmp_meeting, $day->format('d-m-Y'));
			  		array_push($tmp_month, $day->format('F'));
			  	  
				}else{
					
			  		array_push($tmp_meeting, $day->format('d-m-Y'));
			  		array_push($tmp_month, $day->format('F'));
				}

			}	    
			

			$begin = new DateTime( 'NOW' );

			$interval = DateInterval::createFromDateString('last day of next month');
			$period = new DatePeriod($begin, $interval, 6, DatePeriod::EXCLUDE_START_DATE);

			foreach ( $period as $day ){
				
				if(($day->format('D')=="Sat")||($day->format('D')=="Sun")||($day->format('D')=="Fri")){	
			  		
			  		$day->modify("last Thursday");
			  	
			  		array_push($tmp_test, $day->format('d-m-Y'));
			  	
			  	  
				}else{
					
			  		array_push($tmp_test, $day->format('d-m-Y'));
				}
			}

			//Full all the array to pass Correctly to CSV
			$temp=array();

			for($i=0;$i<count($tmp_test);$i++){
				
				array_push($temp,$tmp_month[$i]);
				array_push($temp,$tmp_meeting[$i]);
				array_push($temp,$tmp_test[$i]);
				array_push($data, $temp);
				$temp=array();
			}

			
			 //Save Array to CSV
			array_to_csv($data,'./Test-'.date('dMy').'.csv');
			

	}

}
