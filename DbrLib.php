<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class DbrLib{
    
    public static function addRangeCriteria($criteria, $range, $fieldname)
    {
        
        
            switch ($range)
             {
                 case 'today' :
                   
                     $criteria->addCondition("'$fieldname' = DATE(NOW())" );  // date is database date column field
                 
                 break;  
                 case 'yesterday' :
                   
                     $criteria->addCondition("'$fieldname' = DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)" );  // date is database date column field
                 
                 break;  
                 case 'thisweek' :
                   
                     $criteria->addCondition("WEEK('$fieldname') = WEEK(NOW()) AND YEAR('$fieldname')= YEAR(NOW())" );  // date is database date column field
                 
                 break;  
                 case 'lastweek' :
                   
                     $criteria->addCondition("WEEK('$fieldname') = WEEK(NOW()-1)AND YEAR('$fieldname')= YEAR(NOW())" );  // date is database date column field
                 
                 break; 
                 case 'thismonth' :
                   
                     $criteria->addCondition("MONTH('$fieldname') = MONTH(NOW()) AND YEAR('$fieldname')= YEAR(NOW())" );  // date is database date column field
                 
                 break; 
                 case 'lastmonth' :
                   
                     $criteria->addCondition("MONTH('$fieldname') = MONTH(NOW()-1) AND YEAR('$fieldname')= YEAR(NOW())" );  // date is database date column field
                 
                 break;
                  case 'thisyear' :
                   
                     $criteria->addCondition("YEAR('$fieldname') =  YEAR(NOW())" );  // date is database date column field
                 
                 break;
             }
        
    }
    
}
?>
