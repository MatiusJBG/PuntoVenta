<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\DTOs\RegisterProductDTO;
use Application\UseCases\ListProductsUseCase;
use Application\UseCases\RegisterProductUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ListProductsUseCase    $listProductsUseCase,
        private readonly RegisterProductUseCase $registerProductUseCase,
    ) {}

    public function index(): View
    {
        $products = $this->listProductsUseCase->execute();

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'  => 'required|string|max:150',
            'price' => 'required|numeric|min:0.01|max:1000000',
            'stock' => 'required|integer|min:0|max:100000',
        ]);

        try {
            $registerProductDTO = new RegisterProductDTO(
                name:  $validatedData['name'],
                price: (float) $validatedData['price'],
                stock: (int) $validatedData['stock'],
            );

            $this->registerProductUseCase->execute($registerProductDTO);

            return redirect()->route('products.index')
                ->with('success', 'Producto registrado correctamente.');
        } catch (\DomainException $domainException) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['domain' => $domainException->getMessage()]);
        }
    }
}
