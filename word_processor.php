<?php

function insertWord($word) {
    // create table as order
    $firstCharacterCode = mb_ord($word);
    $firstCharacterCode = "character_" . $firstCharacterCode;
    $worldOfWordsDatabase = new mysqli("localhost", "root", "", "world_of_words");
    $query = $worldOfWordsDatabase->query("select * from $firstCharacterCode where word = '{$word}'");
    if($query = $query->fetch_array(SQLITE3_ASSOC)) {
        return $query['wordId'];
    } else {
        $lastWordId = $worldOfWordsDatabase->query("select max(wordId) as maxId from $firstCharacterCode");
        if($lastWordId = $lastWordId->fetch_array(SQLITE3_ASSOC)) {
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