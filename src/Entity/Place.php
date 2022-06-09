<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"festival"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"festival"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Festival::class, mappedBy="place")
     */
    private $festival;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lng;

    public function __construct()
    {
        $this->festival = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Festival>
     */
    public function getFestival(): Collection
    {
        return $this->festival;
    }

    public function addFestival(Festival $festival): self
    {
        if (!$this->festival->contains($festival)) {
            $this->festival[] = $festival;
            $festival->setPlace($this);
        }

        return $this;
    }

    public function removeFestival(Festival $festival): self
    {
        if ($this->festival->removeElement($festival)) {
            // set the owning side to null (unless already changed)
            if ($festival->getPlace() === $this) {
                $festival->setPlace(null);
            }
        }

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
