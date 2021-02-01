<?php

namespace SharedBookshelf\Controller\Errors;

interface ErrorsController
{
    public function execute(): void;
    public function getExceptionClass(): string;
}
