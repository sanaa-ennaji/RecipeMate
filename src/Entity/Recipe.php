<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "text")]
    private $description;

    #[ORM\Column(type: "text")]
    private $ingredients;

    #[ORM\Column(type: "datetime")]
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    
    }
}
