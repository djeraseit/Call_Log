# Call_Log

Have the ability to call another script if a new call is recieved. (i.e. be able to email or send a message of some sort, with the personal caller id info.)

So there is a script to retrive the call log from the OBIHAI OBi100, and add it to a mysql database, and trigger another script if there are new calls.


## Completed
Send to MQTT
Send to Pushbullet

Lookup the number via various APIs

Automatically report obnoxious call (SPAM)


Instead of using simultaneous ring with a mobile phone and an office phone, we assign one destination as a Twilio number. We then answer (and hang up on!) robocallers.

After a quick, one-time setup, incoming calls are able to be analyzed. If it’s a good caller, we instruct Twilio to return a busy signal and the call continues ringing on your phone, just like normal. Both the caller and the recipient don’t notice anything different from making and receiving a regular phone call.

But if it’s a robocall, we answer the call immediately. To prevent false positives, instead of just hanging up, the caller is presented with an audio CAPTCHA. If the caller passes the test and proves they are human, the call is allowed through. If they fail, we hang up the call.

