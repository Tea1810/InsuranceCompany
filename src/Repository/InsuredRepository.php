<?php

namespace Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entity\Insurance;
use Entity\Insured;
use Validations;

class InsuredRepository extends EntityRepository
{
    private InsuranceRepository $insuranceRepo;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->insuranceRepo = $em->getRepository(Insurance::class);
    }

    public function save(?Insured $customer): void
    {
        $this->getEntityManager()->persist($customer);
        $this->insuranceRepo->createBasicInsurance($customer);

        $this->getEntityManager()->flush();

    }

}