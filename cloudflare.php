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

curl -X GET "https://api.cloudflare.com/client/v4/user/tokens/verify" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN" \
     -H "Content-Type:application/json"

// Write key-value pair

curl -X PUT "https://api.cloudflare.com/client/v4/accounts/01a7362d577a6c3019a474fd6f485823/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/values/My-Key?expiration=1578435000&expiration_ttl=300" \
     -H "X-Auth-Email: user@example.com" \
     -H "X-Auth-Key: c2547eb745079dac9320b638f5e225cf483cc5cfdda41" \
     -H "Content-Type: text/plain" \
     --data '"Some Value"'

     {
        "success": true,
        "errors": [],
        "messages": []
      }

// Read key-value pair

curl -X GET "https://api.cloudflare.com/client/v4/accounts/01a7362d577a6c3019a474fd6f485823/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/values/My-Key" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN"

/* */
     curl -X PUT "https://api.cloudflare.com/client/v4/accounts/aca4b736fda75cdcc7597a422139a8f5/storage/kv/namespaces/5da70ac7fe364717992072cbbb66196c/values/My-Key?expiration=1578435000&expiration_ttl=300" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN"
     -H "Content-Type: text/plain" \
     --data '"Some Value"'
     
curl -X PUT "https://api.cloudflare.com/client/v4/accounts/aca4b736fda75cdcc7597a422139a8f5/storage/kv/namespaces/5da70ac7fe364717992072cbbb66196c/values/My-Key?expiration=1578435000&expiration_ttl=300" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN" \
     -H "Content-Type: text/plain" \
     --data '"Some Value"'
     
     {"success":false,"errors":[{"code":10000,"message":"Authentication error"}]}

{
  "result": null,
  "success": true,
  "errors": [],
  "messages": []
}

curl -X GET "https://api.cloudflare.com/client/v4/accounts/aca4b736fda75cdcc7597a422139a8f5/storage/kv/namespaces/5da70ac7fe364717992072cbbb66196c/values/My-Key" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN"

// PUT multiple key-value pairs
 curl -X PUT "https://api.cloudflare.com/client/v4/accounts/01a7362d577a6c3019a474fd6f485823/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/bulk" \
  -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN" \
     -H "Content-Type: application/json" \
     --data '[{"key":"My-Key","value":"Some string","expiration":1578435000,"expiration_ttl":300,"metadata":{"someMetadataKey":"someMetadataValue"},"base64":false}]'
     
// Delete key-value

curl -X DELETE "https://api.cloudflare.com/client/v4/accounts/01a7362d577a6c3019a474fd6f485823/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/values/My-Key" \
     -H "Authorization: Bearer sFtw3RFOLZsY5GXPNKLrsFa8fNHmf2N1Z7yCnWvN"