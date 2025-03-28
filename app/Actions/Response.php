<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;

class Response
{
    protected bool $status;

    protected array $data = [];

    protected ?string $message = null;

    protected int $code = 200;

    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function statusCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public static function success(): self
    {
        return new self(true);
    }

    public static function error(): self
    {
        return new self(false);
    }

    public function toJson(): JsonResponse
    {
        return response()->json([
            'success' => $this->status,
            'message' => $this->message,
            'data' => !empty($this->data) ? $this->data : null,
        ], $this->code);
    }

    public function __toString(): string
    {
        return $this->toJson()->getContent();
    }

    public function toResponse($request): JsonResponse
    {
        return $this->toJson();
    }
}
