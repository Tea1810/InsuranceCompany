<?php

use Entity\Insurance;

class CheckExpiredInsurances
{

    public static function checkInsurances(Insurance $insurance):bool
    {

        if(self::checkExpirationDate($insurance) ==false)
            return false;
        return true;
    }

    private static function checkExpirationDate(Insurance $insurance)
    {
        $todaysDate=new DateTime();
       return $insurance->checkExpirationDate($todaysDate);
    }
}