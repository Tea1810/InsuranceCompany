<?php

namespace Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entity\Insurance;
use Entity\Insured;
use FormValidations\InsuredValidation;

class InsuredRepository extends EntityRepository
{
    private InsuranceRepository $insuranceRepo;

    private ?string $errors;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->insuranceRepo = $em->getRepository(Insurance::class);
        $this->errors=null;

    }

    public function save():void
    {
        $firstName = htmlspecialchars($_POST['name']);
        $lastName=htmlspecialchars($_POST['surname']);
        $name=$firstName.$lastName;

        $street = htmlspecialchars($_POST['street']);
        $number=htmlspecialchars($_POST['number']);
        $address=$street." nr ".$number;

        $this->errors=InsuredValidation::check($firstName,$lastName,$street,$number);

        if(empty($this->errors))
        {
            $entity=new Insured($name,$address);

            $this->getEntityManager()->persist($entity);
            $this->insuranceRepo->createBasicInsurance($entity);

            $this->getEntityManager()->flush();
            header('Location: /insurances');
            return;
        }
        //header('Location: /customers/new');


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

    public function getErrors()
    {
        return $this->errors;
    }
}