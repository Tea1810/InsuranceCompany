<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Entity\Insurance;
use Entity\Insured;
use Entity\Insurer;
use Entity\Service;
use Repository\InsuranceRepository;
use Twig\Environment;

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
        $this->insuranceRepository->save();

    }
    public function DeleteInsurance(){
        $this->insuranceRepository->delete();
    }
    public function EditInsurance(){
        $this->insuranceRepository->edit();
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


}