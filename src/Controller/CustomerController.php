<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Insured;
use Entity\Service;
use Repository\InsuredRepository;
use Repository\ServiceRep;
use Twig\Environment;
use Validations;

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
        $validation=new Validations();

        $firstName = $_POST['firstName'];
        $firstName=$validation->ValidateUserInput($firstName);

        $lastName=$_POST['lastName'];
        $lastName=$validation->ValidateUserInput($lastName);

        $street = $_POST['street'];
        $street=$validation->ValidateUserInput($street);

        $number=$_POST['number'];
        $number=$validation->ValidateUserInput($number);

        $name=$firstName." ".$lastName;
        $address=$street." nr. ".$number;

        $customer=new Insured($name,$address);

        $this->customerRepository->save($customer);

    }
    public function DisplayNewCustomer()
    {
        return $this->twig->render('Customer/new.html.twig');
    }
    public function DisplayCustomers()
    {
        return $this->twig->render('Customer/customers.html.twig', [
            'customers' => $this->customerRepository->findAll(),
        ]);
    }


}