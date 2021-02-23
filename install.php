<?php

// create storage files if they do not already exist

$datafiles = ['blacklist_names.json', 'blacklist_numbers.json','phone_book.json','phone_history.json','whitelist_names.json','whitelist_numbers.json'];

foreach ($datafiles as $datafile) {
    if(!is_file($datafile)){
        //Some simple example content.
        $contents = '{}';
        //Save our content to the file.
        file_put_contents($file, $contents);
    }
}