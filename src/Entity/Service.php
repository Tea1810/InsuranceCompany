<?php

namespace Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Repository\ServiceRep;

#[ORM\Table(name: 'services')]
#[ORM\Entity(repositoryClass: ServiceRep::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(nullable: true)]
    private ?string $name = null;
    #[ORM\Column(nullable: true)]
    private ?float $price=null;

    #[ORM\ManyToMany(targetEntity: Insurance::class, inversedBy: 'services')]
    private Collection $insurances;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }
    public function setName(string $name): void{
        $this->name=$name;
    }

    public function setPrice(string $price): void
    {
        $this->price=$price;
    }
    public function update(string $name, float $price): void{
        $this->name=$name;
        $this->price=$price;
    }

    /**
     * @return Collection<int, Insurance>
     */
    public function getInsurances(): Collection
    {
        return $this->insurances;
    }
    public function addInsurance(Insurance $insurance): static
    {
        if (!$this->insurances->contains($insurance)) {
            $this->insurances->add($insurance);
            $insurance->addServices($this);
        }
        return $this;
    }

    public function removeInsurance(Insurance $insurance): static
    {
        if ($this->insurances->contains($insurance)){
        $this->insurances->removeElement($insurance);
        $insurance->removeService($this);
        }
        return $this;
    }
    public function sameName(?string $service): ?int
    {
        return ($this->name===$service);
    }
}