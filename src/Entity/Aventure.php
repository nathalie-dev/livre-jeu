<?php

namespace App\Entity;

use App\Repository\AventureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AventureRepository::class)]
class Aventure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'aventureDebutee', cascade: ['persist', 'remove'])]
    private ?Etape $premiereEtape = null;

    #[ORM\OneToMany(mappedBy: 'aventure', targetEntity: Etape::class)]
    private Collection $etapes;

    #[ORM\OneToMany(mappedBy: 'finAventure', targetEntity: Etape::class)]
    private Collection $finsPossibles;

    #[ORM\OneToMany(mappedBy: 'aventure', targetEntity: Partie::class)]
    private Collection $parties;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
        $this->finsPossibles = new ArrayCollection();
        $this->parties = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPremiereEtape(): ?Etape
    {
        return $this->premiereEtape;
    }

    public function setPremiereEtape(?Etape $premiereEtape): self
    {
        $this->premiereEtape = $premiereEtape;

        return $this;
    }

    /**
     * @return Collection<int, Etape>
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): self
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes->add($etape);
            $etape->setAventure($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->removeElement($etape)) {
            // set the owning side to null (unless already changed)
            if ($etape->getAventure() === $this) {
                $etape->setAventure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Etape>
     */
    public function getFinsPossibles(): Collection
    {
        return $this->finsPossibles;
    }

    public function addFinsPossible(Etape $finsPossible): self
    {
        if (!$this->finsPossibles->contains($finsPossible)) {
            $this->finsPossibles->add($finsPossible);
            $finsPossible->setFinAventure($this);
        }

        return $this;
    }

    public function removeFinsPossible(Etape $finsPossible): self
    {
        if ($this->finsPossibles->removeElement($finsPossible)) {
            // set the owning side to null (unless already changed)
            if ($finsPossible->getFinAventure() === $this) {
                $finsPossible->setFinAventure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partie>
     */
    public function getParties(): Collection
    {
        return $this->parties;
    }

    public function addParty(Partie $party): self
    {
        if (!$this->parties->contains($party)) {
            $this->parties->add($party);
            $party->setAventure($this);
        }

        return $this;
    }

    public function removeParty(Partie $party): self
    {
        if ($this->parties->removeElement($party)) {
            // set the owning side to null (unless already changed)
            if ($party->getAventure() === $this) {
                $party->setAventure(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }
}

