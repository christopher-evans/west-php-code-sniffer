<?php
$z = null;

class X
{
    public function y($z = null)
    {

    }
}

abstract class Y
{
    public abstract function  z   (  $z   = null);
}

$x = new X();
$x->y($z);
$x->y();
$x->y(null);
interface S
{
    public function f(string $level, string $message, array $context = [], \DateTimeInterface $time = null);
}

public function g(string $level, string $message, array $context = [], \DateTimeInterface $time = null)
{
    if (null === $time) {
        $time = 1;
    } elseif (null === $time) {
        $time = 2;
    }

    $x = null;

}
final class D
{
    public function __construct(string $message = '', \Throwable $previous = null)
    {

    }
}
?>
