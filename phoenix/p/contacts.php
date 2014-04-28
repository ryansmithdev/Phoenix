<?php

$contacts = (new Contacts)->getData();

?>
    
   <table>
       
       <? foreach( $contacts[0] as $key=>$val):?>
           <th style="text-align:left;padding:5px;"><?=$key?></th>
       <? endforeach; ?>
       
       
       <? foreach( $contacts as $contact):?>
       
       <tr>
           <? foreach( $contact as $key=>$val):?>
           
               <tr><?=$val?></tr>
            
            <? endforeach; ?>
           
       </tr>
       
       <? endforeach; ?>
       
   </table>