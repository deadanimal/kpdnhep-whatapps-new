<!-- ?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mLetter->header;

?-->

<?= 
    $mLetter->header;
    echo "<hr>",
    $mLetter->title,
    $mLetter->body,
    $mLetter->footer;
?>