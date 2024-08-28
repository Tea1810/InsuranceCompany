<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Entity\Insured;
use Entity\Insurer;
use Entity\Service;

class Validations
{
    private array $goodType=['Basic','Private','Dental'];
    private array  $goodStatus=['Active','Inactive'];
    public function ValidateInsurance(?string $UnvalidatedType, ?string $UnvalidatedStatus, mixed $UnvalidatedInsured_id, mixed $UnvalidateInsurer_id, ?array $UnvalidatedServices):?string
    {
        $type= $this->ValidateType($UnvalidatedType);
        $status= $this->ValidateStatus($UnvalidatedStatus);
        $insurer_id=$this->ValidateNumber($UnvalidateInsurer_id);
        $insured_id=$this->ValidateNumber($UnvalidatedInsured_id);
        $services=$this->ValidateServices($UnvalidatedServices);

        if($type && $status && $insurer_id && $insured_id && $services)
            return null;

        return "Insurance can not be saved";
    }


    public function ValidateType(string $type): ?string
    {
        if (in_array($type,$this->goodType)) {
            return $type;
        }

        return null;
    }

    public function ValidateStatus(string $status): ?string
    {
        if (in_array($status,$this->goodStatus)) {
            return $status;
         }

        return null;
    }

    private function ValidateServices(array $UnvalidatedServices): ?array
    {
        foreach ($UnvalidatedServices as $service)
        {
            if(!is_numeric($service))
              {
                  return null;
              }
        }
        return $UnvalidatedServices;
    }

    public function ValidateUserInput(mixed $data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function ValidateNumber(string $data)
    {
        if(is_numeric($data))
            return $data;
        return null;
    }


}