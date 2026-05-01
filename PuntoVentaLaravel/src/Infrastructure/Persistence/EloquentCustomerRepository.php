<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Entities\Customer;
use Domain\Repositories\CustomerRepositoryInterface;
use Infrastructure\Persistence\Eloquent\CustomerModel;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function findById(int $customerId): ?Customer
    {
        $customerModel = CustomerModel::find($customerId);

        return $customerModel ? $this->mapModelToEntity($customerModel) : null;
    }

    public function findByDocumentNumber(string $documentNumber): ?Customer
    {
        $customerModel = CustomerModel::where('DocumentNumber', $documentNumber)->first();

        return $customerModel ? $this->mapModelToEntity($customerModel) : null;
    }

    /** @return Customer[] */
    public function findAll(): array
    {
        return CustomerModel::orderBy('LastName')
            ->get()
            ->map(fn (CustomerModel $model) => $this->mapModelToEntity($model))
            ->all();
    }

    public function save(Customer $customerEntity): Customer
    {
        CustomerModel::create([
            'CustomerId'     => $customerEntity->getCustomerId(),
            'DocumentNumber' => $customerEntity->getDocumentNumber(),
            'FirstName'      => $customerEntity->getFirstName(),
            'LastName'       => $customerEntity->getLastName(),
            'Phone'          => $customerEntity->getPhone(),
            'Email'          => $customerEntity->getEmail(),
            'Address'        => $customerEntity->getAddress(),
            'City'           => $customerEntity->getCity(),
        ]);

        return $customerEntity;
    }

    public function nextId(): int
    {
        $maximumCurrentId = CustomerModel::max('CustomerId');

        return $maximumCurrentId !== null ? (int) $maximumCurrentId + 1 : 1;
    }

    private function mapModelToEntity(CustomerModel $customerModel): Customer
    {
        return new Customer(
            customerId:     (int) $customerModel->CustomerId,
            documentNumber: (string) $customerModel->DocumentNumber,
            firstName:      (string) $customerModel->FirstName,
            lastName:       (string) $customerModel->LastName,
            phone:          $customerModel->Phone,
            email:          $customerModel->Email,
            address:        $customerModel->Address,
            city:           $customerModel->City,
        );
    }
}
