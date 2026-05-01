<?php

declare(strict_types=1);

namespace Application\UseCases;

use Application\DTOs\RegisterCustomerDTO;
use Domain\Entities\Customer;
use Domain\Repositories\CustomerRepositoryInterface;
use Domain\Validation\EcuadorianDocumentValidator;
use Domain\Validation\PhoneValidator;
use DomainException;

class RegisterCustomerUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface  $customerRepository,
        private readonly EcuadorianDocumentValidator  $documentValidator,
        private readonly PhoneValidator               $phoneValidator,
    ) {}

    public function execute(RegisterCustomerDTO $registerCustomerDTO): Customer
    {
        $this->documentValidator->validate($registerCustomerDTO->documentNumber);
        $this->phoneValidator->validate($registerCustomerDTO->phone);

        $this->ensureDocumentNumberIsNotAlreadyRegistered($registerCustomerDTO->documentNumber);

        $nextCustomerId = $this->customerRepository->nextId();

        $customerEntity = new Customer(
            customerId:     $nextCustomerId,
            documentNumber: $registerCustomerDTO->documentNumber,
            firstName:      trim($registerCustomerDTO->firstName),
            lastName:       trim($registerCustomerDTO->lastName),
            phone:          $registerCustomerDTO->phone,
            email:          $registerCustomerDTO->email,
            address:        $registerCustomerDTO->address,
            city:           $registerCustomerDTO->city,
        );

        return $this->customerRepository->save($customerEntity);
    }

    private function ensureDocumentNumberIsNotAlreadyRegistered(string $documentNumber): void
    {
        $existingCustomer = $this->customerRepository->findByDocumentNumber($documentNumber);

        if ($existingCustomer !== null) {
            throw new DomainException(
                "Ya existe un cliente registrado con el documento '{$documentNumber}'."
            );
        }
    }
}
