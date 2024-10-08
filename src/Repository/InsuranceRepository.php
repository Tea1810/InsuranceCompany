<?php

namespace Repository;

use CheckContraints;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Entity\Insurance;
use Entity\Insured;
use Entity\Insurer;
use Entity\Service;

class InsuranceRepository extends EntityRepository
{

    public function save(?Insurance $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
    public function createBasicInsurance(Insured $customer){

        $type='Basic';
        $status='Active';
        $insurer=NULL;
        $services=new ArrayCollection();

        $entity=new Insurance($type, $status, $insurer, $customer, $services);

        $customer->addInsurance($entity);

        $this->getEntityManager()->persist($entity);

    }

    public function delete(int $id):void{

        $insurance=$this->find($id);
        $this->getEntityManager()->remove($insurance);
        $this->getEntityManager()->flush();

    }
    public function edit(?Insurance $insurance, ?string $status,?ArrayCollection $services)
    {
        $insurance->update($status,$services);
        $this->getEntityManager()->flush();

    }


    public function verifyInsurances()
    {
        $insurances=$this->findAll();

        foreach ($insurances as $insurance){
            if(CheckContraints::checkInsurances($insurance)==false){
                   $insurance->update('Inactive',new ArrayCollection());
                   $this->getEntityManager()->flush();
            }
        }

    }

    public function search(?string $sortBy){

        $type = $_GET['type'] ?? null;
        $status = $_GET['status'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $insured_id = $_GET['insured'] ?? null;

        $goodType=['Basic','Private','Dental'];
        $goodStatus=['Active','Inactive'];

        $qb=$this->createQueryBuilder('insurance');

        if (in_array($type,$goodType)) {
            $qb ->andWhere('insurance.type = :type')
                ->setParameter('type', $type);
        }
        if (in_array($status,$goodStatus)) {
            $qb->andWhere('insurance.status = :status')
                ->setParameter('status', $status);
        }
        if ($startDate && $endDate) {
            $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
            $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);

            if ($startDateObj && $endDateObj) {
                $qb->andWhere('insurance.creation_date BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', $startDate)
                    ->setParameter('endDate', $endDate);
            }
        }
        if($insured_id && is_numeric($insured_id))
        {
            $qb->leftJoin('insurance.insured', 'insured')
            ->andWhere('insured.id = :insured_id')
            ->setParameter('insured_id', $insured_id);
        }
        if (!$type && !$status && !$startDate && !$endDate && !$insured_id) {
            return $this->findAll();
        }

        return $this->sort($qb,$sortBy);
    }

    public function sortByEntityName(?QueryBuilder $qb,?string $sortBy)
    {
        $qb ->leftJoin("insurance.$sortBy",$sortBy)
            ->orderBy("$sortBy.name",'ASC');
        return $qb->getQuery()->getResult();

    }


    public function sort(?QueryBuilder $qb,?string $sortBy)
    {
        $goodArray=['type','status','creation_date','insurer','insured'];

        if($sortBy && !in_array($sortBy,$goodArray))
            return null;

        if(!$sortBy)
            return $qb->getQuery()->getResult();

        $sortByEntityName = ["insured", "insurer"];

        if(in_array($sortBy, $sortByEntityName)){
           return $this->sortByEntityName($qb,$sortBy);
        }
        else {
            $qb->orderBy("insurance.$sortBy",'ASC');
            return $qb->getQuery()->getResult();
        }
    }
}