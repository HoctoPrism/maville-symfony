<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @UniqueEntity("name", message="Impossible, cet utilisateur existe déjà !")
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity=Festival::class, mappedBy="tag")
     */
    private $festival;

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
            $festival->addTag($this);
        }

        return $this;
    }

    public function removeFestival(Festival $festival): self
    {
        if ($this->festival->removeElement($festival)) {
            $festival->removeTag($this);
        }

        return $this;
    }

}
