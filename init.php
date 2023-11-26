<?php

$conflictFiles = [
    'container/viewPhpConf.php',
    'container/viewTwigConf.php'
];

foreach ($conflictFiles as $conflictFile) {
    if (file_exists($conflictFile)) {
        unlink($conflictFile);
    }
}
