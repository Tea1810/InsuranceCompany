<?php

namespace Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Console\Command\ClearCache\CollectionRegionCommand;
use Repository\InsuranceRepository;

#[ORM\Table(name: 'insurances')]
#[ORM\Entity(repositoryClass: InsuranceRepository::class)]
class Insurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $type;

    #[ORM\Column(nullable: true)]
    private ?float $pricing = null;

    #[ORM\Column(nullable: true)]
    private string $status;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'insurances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Insured $insured;

    #[ORM\ManyToOne(inversedBy: 'insurances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Insurer $insurer;

    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'insurances')]
    private Collection $services;

    private float $dentalInsurancePrice=500;
    private float $basicInsurancePrice=0;
    public function __construct(string $type, string $status, ?Insurer $insurer, Insured $customer, Collection $services) {

        $this->services=new ArrayCollection();

        $this->type=$type;
        $this->status=$status;
        foreach($services as $service){
            $this->addServices($service);}
        $this->pricing=$this->CalculatePrice();
        $this->insurer=$insurer;
        $this->insured=$customer;

    }
    private function CalculatePrice(){

        if($this->type=='Private')
           return $this->CalculatePrivateIPrice();
        else if($this->type=='Dental')
            return $this->dentalInsurancePrice;
        else
            return $this->basicInsurancePrice;
    }
    private function CalculatePrivateIPrice()
    {
        $price=0;
        foreach ($this->services as $service)
        {
            $price+=$service->getPrice();
        }
        return $price;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getPricing(): float
    {
        return $this->pricing;
    }

    public function setPricing(float $pricing): void
    {
        $this->pricing = $pricing;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
    public function getDentalInsurancePrice(){
        return $this->dentalInsurancePrice;
    }
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }


    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }
    public function addServices(Service $service): static
    {

        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addInsurance($this);
        }
        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->contains($service)){
            $this->services->removeElement($service);
            $service->removeInsurance($this);
        }
        return $this;
    }

    public function getInsurer()
    {
        return $this->insurer;
    }

    public function getInsured()
    {
        return $this->insured;
    }
    public function update(string $status, Collection $services): void{
        $this->status=$status;
        if(!$services->isEmpty()) {
            while(!$this->services->isEmpty() && $this->services[0]!=NULL){
                $this->removeService($this->services[0]);
            }
            foreach ($services as $service) {
                $this->addServices($service);
            }
            $this->pricing = $this->CalculatePrice($this->services);
        }
    }
}