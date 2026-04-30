<?php

namespace Domain\Entities;

class Student
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}