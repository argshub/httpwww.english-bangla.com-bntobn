<?php

$worldOfWordsDatabase = new mysqli("127.0.0.1", "root", "", "mysql");
$worldOfWordsDatabase->query("create database if not exists btob_two CHARACTER SET utf8 COLLATE utf8_general_ci");
$bTobTwo = new mysqli("localhost", "root", "", "btob_two");
$bTobTwo->query("create table if not exists word_definitions (id int primary key auto_increment, word text, definitions text)");

$counter = 0;
if ($file = fopen("btob.txt", "r")) {
    while(!feof($file)) {
        $line = json_decode(fgets($file), true);
        # do same stuff with the $line
        $word = $worldOfWordsDatabase->real_escape_string($line['word']);
        $definitions = $worldOfWordsDatabase->real_escape_string($line['meaning']);

        if(!$bTobTwo->query("select * from word_definitions where word = '{$word}' and definitions = '{$definitions}'")->num_rows) {
            $bTobTwo->query("insert into word_definitions (word, definitions) values ('{$word}','{$definitions}')");
        }
        print_r("\r000000000"  . ++$counter . "->" . $bTobTwo->insert_id);

    }
    fclose($file);
}