<?php 

/**
* Megalith Technologies
*
* @category VoIP
* @author Theodis Butler
* @copyright Copyright (c) 2021 Megalith Technologies (https://megalithtechnologies.com)
* @license https://www.gnu.org/licenses/gpl-3.0.en.html
*
* Call Blocker for stopping annoying, spam and robocalls on Obihai Devices
* Copyright (C) 2021 Theodis Butler
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
*/


$phonehistory = json_decode(file_get_contents('phone_history.json'), true);

foreach ($phonehistory as $contact) {
    
    //$callhistory = $contact['CallHistory'];
   
    if (!empty($contact['Number']) && empty($contact['Name']) && strlen($contact) >= 10) {
        $phonenumber = $contact['Number'];
        $fullname = null;
        //$entry[$phonenumber] = array('Name' => $fullname,'Number'=> $phonenumber);
    
    } elseif (!empty($contact['Name']) && !empty($contact['Number'])) {
        $fullname = $contact['Name'];
        $phonenumber = $contact['Number'];        
    } else {
     continue;
    }
    $entry[$phonenumber] = array('Name' => $fullname);
}
// remove duplicates before inserting into phonebook

//$temp = array_flip($entry);
//$temp2 = array_unqiue($entry);
//$entry = array_flip($entry);

var_dump($entry);
$output = json_encode($entry);
file_put_contents('phone_book.json',$output);