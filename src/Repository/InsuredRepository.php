<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\Insured;
class InsuredRepository extends EntityRepository
{
    public function save(): void
    {
        $name = $_POST['name'];
        $address = $_POST['address'];

        $entity=new Insured($name,$address);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }

    public function delete():void{

        $id=$_POST['deleteCustomer'];
        $customer=$this->find($id);
        $this->getEntityManager()->remove($customer);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }

    public function edit()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $address = $_POST['address'];

        $service=$this->getEntityManager()->find(Insured::class,$id);
        $service->update($name,$address);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }
}