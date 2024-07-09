<?php

namespace Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Entity\Insurance;
use Entity\Insured;
use Entity\Insurer;
use Entity\Service;

class InsuranceRepository extends EntityRepository
{
    public function save(): void
    {
        $type=$_POST['type'];
        $status=$_POST['status'];
        $insurer_id=$this->getEntityManager()->getRepository(Insurer::class)->find($_POST['insurer_id']);
        $insured_id=$this->getEntityManager()->getRepository(Insured::class)->find($_POST['insured_id']);
        $services=$this->createCollection();

        $entity=new Insurance($type, $status, $insurer_id, $insured_id, $services);

        $insured_id->addInsurance($entity);
        $insurer_id->addInsurance($entity);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }
    public function createBasicInsurance(Insured $customer){
        $type='Basic';
        $status='Active';
        $insurer=NULL;
        $services=new ArrayCollection();

        $entity=new Insurance($type, $status, $insurer, $customer, $services);

        $customer->addInsurance($entity);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }

    public function createCollection():Collection{
        $services=new ArrayCollection();
      foreach ($_POST['services'] as $id)
      {
          $service=$this->getEntityManager()->getRepository(Service::class)->find($id);
          $services->add($service);
      }
     return $services;
     }

    public function delete():void{

        $id=$_POST['deleteInsurance'];
        $insurance=$this->find($id);
        $this->getEntityManager()->remove($insurance);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }
    public function edit()
    {
        $id=$_POST['id'];
        $status=$_POST['status'];
        $services=$this->createCollection();

        $insurance=$this->getEntityManager()->find(Insurance::class,$id);

        $insurance->update($status,$services);
        $this->getEntityManager()->flush();
        header('Location: /insurances');
        exit();
    }
}