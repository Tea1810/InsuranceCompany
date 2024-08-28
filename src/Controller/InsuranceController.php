<?php

namespace Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Entity\Insurance;
use Entity\Insured;
use Entity\Insurer;
use Entity\Service;
use Repository\InsuranceRepository;
use Twig\Environment;
use Validations;

class InsuranceController
{
    private EntityManager $entityManager;
    private InsuranceRepository $insuranceRepository;
    private Environment $twig;

    public function __construct(EntityManager $entityManager,Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig=$twig;
        $this->insuranceRepository= $this->entityManager->getRepository(Insurance::Class);

    }
    public function DisplayInsurances()
    {
        $sortBy= $_POST['sort'] ?? null;

        $this->insuranceRepository->verifyInsurances();

        if(isset($_GET['filterBy']))
            $insurances= $this->FilterInsurances($sortBy);
        else
            $insurances=$this->SortInsurances($sortBy);


        return $this->twig->render('Insurance/insurance.html.twig', [
        'insurances' => $insurances,
        'customers'=>$this->entityManager->getRepository(Insured::class)->findAll(),
    ]);

    }


    public function DisplayNewInsurance()
    {

        return $this->twig->render('Insurance/new.html.twig', [
            'insurances' => $this->insuranceRepository->findAll(),
            'insurers'=>$this->entityManager->getRepository(Insurer::class)->findAll(),
            'customers'=>$this->entityManager->getRepository(Insured::class)->findAll(),
            'services'=>$this->entityManager->getRepository(Service::class)->findAll(),
        ]);
    }
    public function CreateInsurance()
    {
        $validation=new Validations();

        $type= $validation->ValidateUserInput($_POST['type']);
        $status=$validation->ValidateUserInput($_POST['status']);
        $insurer_id=$validation->ValidateUserInput($_POST['insurer_id']);
        $insured_id=$validation->ValidateUserInput($_POST['insured_id']);

        $userInputServices=$_POST['services'];
        $services=[];
        foreach ($userInputServices as $service)
            $services[]=$validation->ValidateUserInput($service);

        $error=$validation->ValidateInsurance($type,$status,$insured_id,$insurer_id,$services);

        if(!$error)
        $this->insuranceRepository->save($this->CreateInsuranceObject($type, $status, $insurer_id, $insured_id, $services));

    }
    public function DeleteInsurance(){

        $validation=new Validations();

        $id=$_POST['deleteInsurance'];
        $id=$validation->ValidateUserInput($id);
        $id=$validation->ValidateNumber($id);

        $this->insuranceRepository->delete($id);
    }
    public function EditInsurance(){

        $validation=new Validations();

        $id=$_POST['id'];
        $id=$validation->ValidateUserInput($id);
        $id=$validation->ValidateNumber($id);

        $status=$_POST['status'];
        $status=$validation->ValidateUserInput($status);
        $status=$validation->ValidateStatus($status);

        $services=($_POST['services'])?$this->CreateCollection($_POST['services']): new ArrayCollection();

        $insurance=$this->entityManager->find(Insurance::class,$id);

        $this->insuranceRepository->edit($insurance,$status,$services);
    }
    public function DisplayEditInsurance($id)
    {
        $insurance = $this->insuranceRepository->find($id);

        return $this->twig->render('Insurance/edit.html.twig', [
            'insurance' => $insurance,
            'services'=>$this->entityManager->getRepository(Service::class)->findAll(),
        ]);
    }

    private function FilterInsurances(?string $sortBy)
    {
        return $this->insuranceRepository->search($sortBy);
    }

    private function SortInsurances(?string $sortBy)
    {
        return $this->insuranceRepository->sort($this->insuranceRepository->createQueryBuilder('insurance'),$sortBy);
    }

    private function CreateInsuranceObject(string $type, string $status, int $insurer_id, int $insured_id, array $servicesArray)
    {
        $insurer=$this->entityManager->getRepository(Insurer::class)->find($insurer_id);
        $insured=$this->entityManager->getRepository(Insured::class)->find($insured_id);
        $services= $this->CreateCollection($servicesArray);

        $entity=new Insurance($type, $status, $insurer, $insured, $services);

        $insured->addInsurance($entity);
        $insurer->addInsurance($entity);

        return $entity;

    }

    private function CreateCollection(array $servicesArray)
    {
        $services=new ArrayCollection();
        foreach ($servicesArray as $id)
        {
            $service=$this->entityManager->getRepository(Service::class)->find($id);
            $services->add($service);
        }
        return $services;
    }




}