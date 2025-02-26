<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class Error
{
    public string $message = 'Internal server error';
    public int $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function __construct(string $message, ?int $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function getObject(): array
    {
        return [
            "message" => $this->message,
            "code" => $this->code
        ];
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}