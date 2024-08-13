<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Insured;
use Entity\Service;
use Repository\InsuredRepository;
use Repository\ServiceRep;
use Twig\Environment;

class CustomerController
{
    private EntityManager $entityManager;
    private InsuredRepository $customerRepository;
    private Environment $twig;

    public function __construct(EntityManager $entityManager,Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig=$twig;
        $this->customerRepository= $this->entityManager->getRepository(Insured::Class);

    }
    public function CreateCustomer()
    {
        $this->customerRepository->save();
    }

    public function getErrors()
    {
        return $this->customerRepository->getErrors();
    }
    public function DisplayNewCustomer()
    {
        $errors=$this->customerRepository->getErrors();
        return $this->twig->render('Customer/new.html.twig',[
            'errors'=>$errors,
        ]);
    }
    public function DisplayCustomers()
    {
        return $this->twig->render('Customer/customers.html.twig', [
            'customers' => $this->customerRepository->findAll(),
        ]);
    }
    public function EditCustomer(){
        $this->customerRepository->edit();
    }
    public function DisplayEditCustomer($id)
    {
        $service = $this->customerRepository->find($id);
        return $this->twig->render('Customer/edit.html.twig', [
            'customer' => $service,
        ]);
    }

    public function DeleteCustomer(){
        $this->customerRepository->delete();
    }
}