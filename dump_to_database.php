<?php
require_once "word_processor.php";

$worldOfWordsDatabase = new mysqli("127.0.0.1", "root", "", "mysql");
$worldOfWordsDatabase->query("create database if not exists world_of_words CHARACTER SET utf8 COLLATE utf8_general_ci");
$worldOfWordsDatabase->query("create database if not exists btob_two CHARACTER SET utf8 COLLATE utf8_general_ci");
$worldOfWordsDatabase = new mysqli("localhost", "root", "", "world_of_words");
$bTobTwo = new mysqli("localhost", "root", "", "btob_two");
$bTobTwo->query("create table if not exists word_definitions (id int primary key auto_increment, wordId int, definitions text)");

$counter = 0;
if ($file = fopen("btob.txt", "r")) {
    while(!feof($file)) {
        $line = json_decode(fgets($file), true);
        # do same stuff with the $line
        $wordId = insertWord($worldOfWordsDatabase->real_escape_string($line['word']));
        $definitions = $worldOfWordsDatabase->real_escape_string($line['meaning']);

        $bTobTwo->query("insert into word_definitions (wordId, definitions) values ($wordId,'{$definitions}')");
        print_r($counter++ . "\n");
    }
    fclose($file);
}
