<?php
$z = null;

class X
{
    public function y($z = null)
    {

    }
}

$x = new X();
$x->y($z);
$x->y();
$x->y(null);
?>
