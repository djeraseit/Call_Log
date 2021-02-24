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

// permission needed: com.cloudflare.edge.storage.kv.key.update
// reference: https://api.cloudflare.com/#workers-kv-namespace-write-key-value-pair

// Verify token

// Write key-value pair
/*
     {
        "success": true,
        "errors": [],
        "messages": []
      }
*/
// Read key-value pair

/*


     
     {"success":false,"errors":[{"code":10000,"message":"Authentication error"}]}

{
  "result": null,
  "success": true,
  "errors": [],
  "messages": []
}

 */
// PUT multiple key-value pairs

// Delete key-value
