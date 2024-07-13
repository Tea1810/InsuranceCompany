<?php

namespace Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entity\Insurance;
use Entity\Insured;
class InsuredRepository extends EntityRepository
{
    private InsuranceRepository $insuranceRepo;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->insuranceRepo = $em->getRepository(Insurance::class);
    }

    public function save(): void
    {
        $name = $_POST['name'];
        $address = $_POST['address'];

        $entity=new Insured($name,$address);

        $this->getEntityManager()->persist($entity);
        $this->insuranceRepo->createBasicInsurance($entity);

        $this->getEntityManager()->flush();
        header('Location: /insurances');

    }

    public function delete():void{

        $id=$_POST['deleteCustomer'];
        $customer=$this->find($id);
        $this->getEntityManager()->remove($customer);
        $this->getEntityManager()->flush();
        header('Location: /insurances');

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

    }
}