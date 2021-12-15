<?php

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

        if(!$bTobTwo->query("select * from word_definitions where wordId = {$wordId} and definitions = '{$definitions}'")->num_rows) {
            $bTobTwo->query("insert into word_definitions (wordId, definitions) values ($wordId,'{$definitions}')");
        }
        print_r("\r000000000"  . $wordId . "->" . $bTobTwo->insert_id);

    }
    fclose($file);
}

function insertWord($word) {
    // create table as order
    $firstCharacterCode = mb_ord($word);
    $firstCharacterCode = "character_" . $firstCharacterCode;
    global $worldOfWordsDatabase;
    if($query = $worldOfWordsDatabase->query("select * from $firstCharacterCode where word = '{$word}'")->fetch_array(MYSQLI_ASSOC)) {
        return $query['wordId'];
    } else {
        if($lastWordId = $worldOfWordsDatabase->query("select max(wordId) as maxId from $firstCharacterCode")->fetch_array(MYSQLI_ASSOC)) {
            $lastWordId = $lastWordId['maxId'];
            if($lastWordId <= 0) {
                $lastWordId = mb_ord($word) * 100000;
            } else {
                $lastWordId++;
            }
        } else {
            $lastWordId = mb_ord($word) * 100000;
        }
        $worldOfWordsDatabase->query("insert into $firstCharacterCode (wordId, word) values ($lastWordId, '{$word}')");
        return $lastWordId;
    }
}