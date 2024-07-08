<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Service;
use Repository\ServiceRep;
use Twig\Environment;
class ServiceController
{
    private EntityManager $entityManager;
    private ServiceRep $serviceRepository;
    private Environment $twig;

    public function __construct(EntityManager $entityManager,Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig=$twig;
        $this->serviceRepository= $this->entityManager->getRepository(Service::Class);

    }
    public function CreateService()
    {
        $this->serviceRepository->save();

    }
    public function DisplayServices()
    {
        return $this->twig->render('Service/service.html.twig', [
            'services' => $this->serviceRepository->findAll(),
        ]);
    }
    public function EditService(){
        $this->serviceRepository->edit();
    }
    public function DisplayEditService($id)
    {
        $service = $this->serviceRepository->find($id);
        return $this->twig->render('Service/edit.html.twig', [
            'service' => $service,
        ]);
    }

    public function DeleteService(){
        $this->serviceRepository->delete();
    }
}