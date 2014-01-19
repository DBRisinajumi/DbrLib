<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class DbrLib{
    
    private static function getDates(){
        
        $sql =  "SELECT ".
		"DATE_FORMAT(CURDATE(),'%Y-%m-%d') cur_day, ".
		"DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 Day), '%Y-%m-%d') prev_day, ".
                 "DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL +1 Day), '%Y-%m-%d') next_day, ".  
		"DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 WEEK), '%Y-%m-%d') prev_seven_day, ".
		"DATE_FORMAT(STR_TO_DATE(CONCAT(PERIOD_ADD(DATE_FORMAT(CURDATE(),'%Y%m'),-2),'01'),'%Y%m%d'), '%Y-%m-%d') three_months_first_day, ".
		"DATE_FORMAT(STR_TO_DATE(CONCAT(PERIOD_ADD(DATE_FORMAT(CURDATE(),'%Y%m'),-1),'01'),'%Y%m%d'), '%Y-%m-%d') prev_month_first_day, ".
		"DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 WEEK), '%Y-%m-%d') prev_seven_day, ".
		"DATE_FORMAT(LAST_DAY(  STR_TO_DATE(CONCAT(PERIOD_ADD(DATE_FORMAT(CURDATE(),'%Y%m'),-1),'01'),'%Y%m%d')   ), '%Y-%m-%d') prev_month_last_day, ".
		"CONCAT( DATE_FORMAT(CURDATE(),'%Y'), '-01-01') cur_year_first_day, ".
		"CONCAT(DATE_FORMAT(CURDATE(),'%Y'), '-12-31') cur_year_last_day, ".
		"CONCAT(DATE_FORMAT(CURDATE(),'%Y-%m'), '-01') cur_month_first_day, ".
		"DATE_FORMAT(LAST_DAY(CURDATE()), '%Y-%m-%d') cur_month_last_day ";
        
        $aDates = Yii::app()->db->createCommand($sql)->queryRow();

	$aToday = getdate();
	if ($aToday['wday'] == 0) $aToday['wday'] = 7; // fix sunday

	$aDates['cur_week_monday'] = date('Y-m-d',mktime(0,0,0,$aToday['mon'],$aToday['mday']-$aToday['wday']+1,$aToday['year']));
	$aDates['cur_week_sunday'] = date('Y-m-d',mktime(0,0,0,$aToday['mon'],$aToday['mday']-$aToday['wday']+7,$aToday['year']));
        
      	$aDates['last_week_monday'] = date('Y-m-d',mktime(0,0,0,$aToday['mon'],$aToday['mday']-$aToday['wday']+1-7,$aToday['year']));
	$aDates['last_week_sunday'] = date('Y-m-d',mktime(0,0,0,$aToday['mon'],$aToday['mday']-$aToday['wday']+7-7,$aToday['year']));

        $aDates['prev_month_first_day'] = date('Y-m-d',mktime(0,0,0,$aToday['mon']-1,1,$aToday['year']));    
	$aDates['prev_month_last_day'] = date('Y-m-d',mktime(0,0,0,$aToday['mon'],0,$aToday['year']));

	$aDates['this_month_name'] =  date('F');
        $aDates['prev_month_name'] =  date('F', strtotime("last month"));
        
	return $aDates;
        
        
    }

    public static function getRangeDate($range = 'all'){
        
        $result['from'] = '';
        $result['to'] = '';
        
        switch ($range) {
            
            case 'today' :  $result['from'] = date('d/M/Y'); break;
            case 'yesterday' :  
                $yesterday = $date->sub(new DateInterval('P1D')); 
                $result['from'] =  $yesterday->format('d/m/Y');
            break;
        
           case 'thisweek' :  
              
                 $today = date('D'); //Or add your own date
                 $start_of_week = date('d/m/Y');
                 $end_of_week = date('d/m/Y');

                 if($today != "Mon")     $start_of_week = date('d/m/Y', strtotime("last monday"));
                 if($today != "Sun")     $end_of_week = date('d/m/Y', strtotime("next sunday"));


                 $result['from'] =  $start_of_week;
                 $result['to'] =  $end_of_week;
                 
            break;
            
            case 'lastweek' :  
              
                 $date = new DateTime();
                 $lastweek = $date->sub(new DateInterval('P7D')); 
                 $lastweek_day = $lastweek->format('D'); 
                 $start_of_week = $lastweek->format('d/m/Y');
                 $end_of_week =  $lastweek->format('d/m/Y');

                 if($lastweek_day != "Mon")     $start_of_week = date('d/m/Y', strtotime("last monday",$lastweek->getTimestamp()));
                 if($lastweek_day != "Sun")     $end_of_week = date('d/m/Y', strtotime("next sunday",$lastweek->getTimestamp()));


                 $result['from'] =  $start_of_week;
                 $result['to'] =  $end_of_week;
                 
            break;
            
             case 'thismonth' :  
              
                
                 $result['from'] =  date('01/m/Y');
                 $result['to'] =  date('t/m/Y');
                 
            break;
            
            
            
                
        }
        
        return $result;
        
    }
    
    public static function getRangeMenuArray($range = null, $range_param){
    
    $aDates = DbrLib::getDates();    
        
    $date = new DateTime();
    $yesterday = $date->sub(new DateInterval('P1D'));  
    $lastmonth =  Yii::t('dbr_app',  date('F', strtotime("last month")));
    $date = new DateTime();
    $lastweek = $date->sub(new DateInterval('P7D')); 
        
    if (!isset($range)) $range = 'all';    
    $aMenuRange[] = array(
        'label'   => Yii::t('dbr_app', 'All'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                      'range' => 'all' 
                    )
                ),
        'icon' => 'calendar',
        'active'  => ($range === 'all')
    );
$aMenuRange[] = array(
        'label'   => Yii::t('dbr_app', 'Today').'('.$aDates['cur_day'].')',
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array( 
                      'range' => 'today' , "$range_param" => $aDates['cur_day'].' - '.$aDates['cur_day']
                    )
                ),
        'active'  => ($range === 'today')
    );
   
   
 $aMenuRange[] = array(
        'label'   =>   Yii::t('dbr_app', 'Yesterday').'('.$aDates['prev_day'].')',
            'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                      'range' => 'yesterday' ,"$range_param" => $aDates['prev_day'].' - '.$aDates['prev_day']
                    )
                ),
     'active'  => ($range === 'yesterday')
    );
     $aMenuRange[] = array(
        'label'   =>   Yii::t('dbr_app', 'This week').'('.(int)date('W').')',
         'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thisweek' ,"$range_param" => $aDates['cur_week_monday'].' - '.$aDates['cur_week_sunday']
                    )
                ),
     'active'  => ($range === 'thisweek')
    );
     
     $aMenuRange[] = array(
         'label'   =>   Yii::t('dbr_app', 'Last week').'('.(int)$lastweek->format('W').')',
         'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'lastweek' ,"$range_param" => $aDates['last_week_monday'].' - '.$aDates['last_week_sunday']
                    )
                ),
     'active'  => ($range === 'lastweek')
    ); 
     
      $aMenuRange[] = array(
        'label'   =>   Yii::t('dbr_app',date('F')),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thismonth' ,"$range_param" =>  $aDates['cur_month_first_day'].' - '.$aDates['cur_month_last_day']
                    )
                ),
    'active'  => ($range === 'thismonth')
    );
      
     $aMenuRange[] = array(
         'label'   =>   $lastmonth,
         'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'lastmonth' ,"$range_param" =>  $aDates['prev_month_first_day'].' - '.$aDates['prev_month_last_day']
                    )
                ),
     'active'  => ($range === 'lastmonth')
    );  
     
     $aMenuRange[] = array(
         'label'   =>   Yii::t('dbr_app', 'This year').'('.(int)(date('Y')).')',
         'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thisyear' ,"$range_param" =>  $aDates['cur_year_first_day'].' - '.$aDates['cur_year_last_day']
                    )
                ),
     'active'  => ($range === 'thisyear')
    );   
     
    return  $aMenuRange;
    }


   
    
    public static function rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}
    
}
?>
