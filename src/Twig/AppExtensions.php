<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use App\Classe\Cart;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
private $categoryRepository;
private $cart;

public function __construct(CategoryRepository $categoryRepository, Cart $cart)
{
    $this->categoryRepository = $categoryRepository;
    $this->cart = $cart;
    
}

    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullcartQuantity' => $this->cart->fullQuantity()
        ];
    }
}