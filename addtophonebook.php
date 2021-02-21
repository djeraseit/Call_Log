<?php
if (!empty($phonenumber)) {
$phonebook = json_decode(file_get_contents('phone_book.json'), true);
$phonebook[$phonenumber]['Name'] = $fullname;
$phonebook[$phonenumber]['LastCall'] = $lastcalldatetime;

file_put_contents('phone_book.json',json_encode($phonebook), FILE_APPEND | LOCK_EX);
}