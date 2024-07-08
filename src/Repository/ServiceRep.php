<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\Service;

class ServiceRep extends EntityRepository
{
    public function save(): void
    {
        $name = $_POST['name'];
        $price = $_POST['price'];

        $entity=new Service($name,$price);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        header('Location: /services');
        exit();
    }

    public function delete():void{

        $id=$_POST['deleteService'];
        $service=$this->find($id);
        $this->getEntityManager()->remove($service);
        $this->getEntityManager()->flush();
        header('Location: /services');
        exit();
}

    public function edit()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $service=$this->getEntityManager()->find(Service::class,$id);
        $service->update($name,$price);
        $this->getEntityManager()->flush();
        header('Location: /services');
        exit();
    }
}