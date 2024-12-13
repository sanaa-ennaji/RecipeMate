<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/recipies',name: 'api_recipes_' )]
class RecipeApiController extends AbstractController
{
   private EntityManagerInterface $entityManager ;

   public function  __construct(EntityManagerInterface $entityManager){
    $this->entityManager = $entityManager;

   }

   #[Route('', name: 'list', methods: ['GET'])]
   public function list(): JsonResponse
   {
    $recipes = $this->entityManger->getRepository(Recipe::class)->findAll();
    $data = [];
    foreach($recipes as $recipe){
     $data[] = [
        'id' =>$recipe->getId(),
        'name' => $recipe->getName(),
        'description' =>$recipe->getDescription(),
        'ingredients' =>$recipe->getIngreients(),
        'createdAt' =>$recipe->getCreatedAt()->format('y-m-d H:i:s'),
     ];
    }
    return $this->json($data);
   }

   #[Route('/{id}', name: 'show', methods: ['GET'])]
   public function show (int $id) :JsonResponse
   {
    $recipe = $this->entityManager->getRepository(Recipe::class)->finc($id);
    if(!recipe){
        return $this->json(['error' => 'recipe not found'], 404);
    }
    return $this->json([
        'id' =>$recipe->getId(),
        'name' => $recipe->getName(),
        'description'=>$recipe->getDescription(),
        'ingredients'=>$recipe->getIngredients(),
        'createdAt' =>$recipe->getCreatedAt()->format('Y-m-d H:i:s'),
    ]);
   }
   #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['description'], $data['ingredients'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $recipe = new Recipe();
        $recipe->setName($data['name']);
        $recipe->setDescription($data['description']);
        $recipe->setIngredients($data['ingredients']);
        $recipe->setCreatedAt(new \DateTime());

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json(['message' => 'Recipe created successfully'], 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return $this->json(['error' => 'Recipe not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $recipe->setName($data['name']);
        }
        if (isset($data['description'])) {
            $recipe->setDescription($data['description']);
        }
        if (isset($data['ingredients'])) {
            $recipe->setIngredients($data['ingredients']);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Recipe updated successfully']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return $this->json(['error' => 'Recipe not found'], 404);
        }

        $this->entityManager->remove($recipe);
        $this->entityManager->flush();

        return $this->json(['message' => 'Recipe deleted successfully']);
    }


}
