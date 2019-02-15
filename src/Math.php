<?php

namespace WbApp;

class Math
{
    public static function getDistanceBetweenTwoPoints(array $pointOne, array $pointTwo): float
    {
        $squareOne = pow(($pointTwo[0] - $pointOne[0]), 2);
        $squareTwo = pow(($pointTwo[1] - $pointOne[1]), 2);

        return sqrt($squareOne + $squareOne);
    }
}
