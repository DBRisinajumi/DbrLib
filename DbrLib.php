<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class DbrLib{
    
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
    
    public static function getRangeMenuArray($range = null){
       
    $date = new DateTime();
    $yesterday = $date->sub(new DateInterval('P1D'));  
    $lastmonth =   date('F', strtotime("last month"));
    $date = new DateTime();
    $lastweek = $date->sub(new DateInterval('P7D')); 
        
    if (!isset($range)) $range = 'all';    
    $aMenuRange[] = array(
        'label'   => Yii::t('dbr_app', 'All'),
        'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                      'range' => 'all' 
                    )
                ),
        'active'  => ($range === 'all')
    );
$aMenuRange[] = array(
        'label'   => Yii::t('dbr_app', 'Today').'('.date('d/m/Y').')',
     'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                      'range' => 'today' 
                    )
                ),
        'active'  => ($range === 'today')
    );
   
   
 $aMenuRange[] = array(
        'label'   =>   Yii::t('dbr_app', 'Yesterday').'('.$yesterday->format('d/m/Y').')',
      'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                      'range' => 'yesterday' 
                    )
                ),
     'active'  => ($range === 'yesterday')
    );
     $aMenuRange[] = array(
        'label'   =>   Yii::t('dbr_app', 'This week').'('.(int)date('W').')',
          'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thisweek' 
                    )
                ),
     'active'  => ($range === 'thisweek')
    );
     
     $aMenuRange[] = array(
         'label'   =>   Yii::t('dbr_app', 'Last week').'('.(int)$lastweek->format('W').')',
          'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'lastweek' 
                    )
                ),
     'active'  => ($range === 'lastweek')
    ); 
     
      $aMenuRange[] = array(
        'label'   =>   date('F'),
           'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thismonth' 
                    )
                ),
    'active'  => ($range === 'thismonth')
    );
      
     $aMenuRange[] = array(
         'label'   =>   $lastmonth,
          'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'lastmonth' 
                    )
                ),
     'active'  => ($range === 'lastmonth')
    );  
     
     $aMenuRange[] = array(
         'label'   =>   Yii::t('dbr_app', 'This year').'('.(int)(date('Y')).')',
          'itemOptions' => array('class' => 'nav-condensed'),
        'url'     => Yii::app()->controller->createUrl(
                'admin',
                array(
                       'range' => 'thisyear' 
                    )
                ),
     'active'  => ($range === 'thisyear')
    );   
     
    return  $aMenuRange;
    }


    public static function addRangeCriteria($criteria, $range, $fieldname)
    {
        
        
            switch ($range)
             {
                 case 'today' :
                   
                     $criteria->addCondition("$fieldname = DATE(NOW())" );  // date is database date column field
                 
                 break;  
                 case 'yesterday' :
                   
                     $criteria->addCondition("$fieldname = DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)" );  // date is database date column field
                 
                 break;  
                 case 'thisweek' :
                   
                     $criteria->addCondition("WEEK($fieldname) = WEEK(NOW()) AND YEAR($fieldname)= YEAR(NOW())" );  // date is database date column field
                 
                 break;  
                 case 'lastweek' :
                   
                     $criteria->addCondition("WEEK($fieldname) = WEEK(DATE_SUB(DATE(NOW()), INTERVAL 7 DAY))AND YEAR($fieldname)= YEAR(NOW())" );  // date is database date column field
                 
                 break; 
                 case 'thismonth' :
                   
                     $criteria->addCondition("MONTH($fieldname) = MONTH(NOW()) AND YEAR($fieldname)= YEAR(NOW())" );  // date is database date column field
                 
                 break; 
                 case 'lastmonth' :
                   
                     $criteria->addCondition("MONTH($fieldname) = MONTH(DATE_SUB(DATE(NOW()), INTERVAL 1 MONTH)) AND YEAR($fieldname)= YEAR(NOW())" );  // date is database date column field
                 
                 break;
                  case 'thisyear' :
                   
                     $criteria->addCondition("YEAR($fieldname) =  YEAR(NOW())" );  // date is database date column field
                 
                 break;
             }
        
    }
    
    public static function rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}
    
}
?>
