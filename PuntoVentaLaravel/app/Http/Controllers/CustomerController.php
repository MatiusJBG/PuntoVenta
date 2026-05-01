<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\DTOs\RegisterCustomerDTO;
use Application\UseCases\ListCustomersUseCase;
use Application\UseCases\RegisterCustomerUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly ListCustomersUseCase    $listCustomersUseCase,
        private readonly RegisterCustomerUseCase $registerCustomerUseCase,
    ) {}

    public function index(): View
    {
        $customers = $this->listCustomersUseCase->execute();

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'documentNumber' => 'required|string|max:20',
            'firstName'      => 'required|string|max:100',
            'lastName'       => 'required|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'address'        => 'nullable|string|max:200',
            'city'           => 'nullable|string|max:100',
        ]);

        try {
            $registerCustomerDTO = new RegisterCustomerDTO(
                documentNumber: $validatedData['documentNumber'],
                firstName:      $validatedData['firstName'],
                lastName:       $validatedData['lastName'],
                phone:          $validatedData['phone'] ?? null,
                email:          $validatedData['email'] ?? null,
                address:        $validatedData['address'] ?? null,
                city:           $validatedData['city'] ?? null,
            );

            $this->registerCustomerUseCase->execute($registerCustomerDTO);

            return redirect()->route('customers.index')
                ->with('success', 'Cliente registrado correctamente.');
        } catch (\DomainException $domainException) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['domain' => $domainException->getMessage()]);
        }
    }
}
