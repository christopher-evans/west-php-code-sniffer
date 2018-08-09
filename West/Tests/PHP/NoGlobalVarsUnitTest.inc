<?php
global $x;

$x = 17;
class X
{
    public function y()
    {
        global $x;

        $x = 12;
    }
}

$GLOBALS['y'] = 13;
?>
