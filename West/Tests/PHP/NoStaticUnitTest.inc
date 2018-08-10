<?php
static $z = 15;

class Y
{
    public static $x = 15;

    static $y = 17;

    protected static function z()
    {

    }
}

abstract class Z
{
    abstract static protected function x();

    static protected abstract function y();
}

class X
{
    private static $t = 17;

    private static function t()
    {

    }

    final static private function s()
    {

    }
}
?>
