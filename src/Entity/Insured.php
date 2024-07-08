<?php

namespace Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Repository\InsuredRepository;

#[ORM\Table(name: 'insured')]
#[ORM\Entity(repositoryClass: InsuredRepository::class)]
class Insured
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $address=null;

    #[ORM\OneToMany(mappedBy: 'insured', targetEntity: Insurance::class, cascade: ['persist', 'remove'])]
    private ?Collection $insurances;

    public function __construct($name, $address) {
        $this->name = $name;
        $this->address = $address;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setName(string $name): void{
        $this->name=$name;
    }
    public function setAddress(string $address): void
    {
        $this->address=$address;
    }
    public function update(string $name, string $address): void{
        $this->name=$name;
        $this->address=$address;
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
            }
        return $this;
    }

    public function removeInsurance(Insurance $insurance): static
    {
        if ($this->insurances->contains($insurance))
            $this->insurances->removeElement($insurance);
        return $this;
    }

}