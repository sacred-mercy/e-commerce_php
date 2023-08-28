<?php

function getSafeValue($value)
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    $value = pg_escape_string($GLOBALS['db'], $value);
    return $value;
}

?>