<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\DTOs\RegisterSaleDTO;
use Application\DTOs\SaleItemDTO;
use Application\UseCases\ListProductsUseCase;
use Application\UseCases\ListSalesUseCase;
use Application\UseCases\RegisterSaleUseCase;
use Infrastructure\Persistence\Eloquent\CustomerModel;
use Infrastructure\Persistence\Eloquent\PaymentMethodModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(
        private readonly ListSalesUseCase    $listSalesUseCase,
        private readonly RegisterSaleUseCase $registerSaleUseCase,
        private readonly ListProductsUseCase $listProductsUseCase,
    ) {}

    public function index(): View
    {
        $sales = $this->listSalesUseCase->execute();

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $customers      = CustomerModel::orderBy('LastName')->get();
        $paymentMethods = PaymentMethodModel::all();
        $products       = $this->listProductsUseCase->execute();

        return view('sales.create', compact('customers', 'paymentMethods', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'customerId'       => 'required|integer',
            'paymentMethodId'  => 'required|integer',
            'items'            => 'required|array|min:1',
            'items.*.productId'=> 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice'=> 'required|numeric|min:0.01',
        ]);

        try {
            $saleItemDTOs = array_map(
                fn (array $item) => new SaleItemDTO(
                    productId: (int) $item['productId'],
                    quantity:  (int) $item['quantity'],
                    unitPrice: (float) $item['unitPrice'],
                ),
                $validatedData['items']
            );

            $registerSaleDTO = new RegisterSaleDTO(
                customerId:      (int) $validatedData['customerId'],
                paymentMethodId: (int) $validatedData['paymentMethodId'],
                saleItems:       $saleItemDTOs,
            );

            $this->registerSaleUseCase->execute($registerSaleDTO);

            return redirect()->route('sales.index')
                ->with('success', 'Venta registrada correctamente.');
        } catch (\DomainException $domainException) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['domain' => $domainException->getMessage()]);
        }
    }
}
