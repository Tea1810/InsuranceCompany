<?php

namespace FormValidations;

class InsuredValidation
{

    public static function check(string $firstName, string $lastName, string $street, string $number)
    {
        if(empty($firstName))
            return "First name can not be empty";
        if(empty($lastName))
            return "Last name can not be empty";
        if(empty($street))
            return "Address can not be empty";
        if(empty($number))
            return "Number can not be empty";

        if(!preg_match("/^[a-zA-Z-' ]*$/",$firstName))
            return "Name can only contain letters";
        if(!preg_match("/^[a-zA-Z-' ]*$/",$lastName))
            return "Name can only contain letters";
        if(!preg_match("/^[a-zA-Z-' ]*$/",$street))
            return "Street can only contain letters";
        if(!preg_match("/^[0-9' ]*$/",$number))
            return "Street can only contain numbers";

        return null;
    }
}