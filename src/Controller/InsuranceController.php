<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
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
    public function DisplayInsurances(?string $sortBy)
    {
        $this->insuranceRepository->verifyInsurances();
        $sortByEntityName=["insured","insurer"];
        $insurances=$sortBy
                ? (in_array($sortBy, $sortByEntityName))
                   ? $this->SortByEntityName($sortBy)
                   : $this->insuranceRepository->findBy([],[$sortBy=>'ASC'])
                : $this->insuranceRepository->findAll();


        return $this->twig->render('Insurance/insurance.html.twig', [
        'insurances' => $insurances,
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

    private function SortByEntityName(?string $sortBy)
    {
        $q = $this->insuranceRepository->createQueryBuilder('i')
            ->leftJoin("i.$sortBy",$sortBy)
            ->orderBy("$sortBy.name",'ASC');
        return $q->getQuery()->getResult();
    }

}