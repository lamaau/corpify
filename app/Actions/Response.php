<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Response
{
    protected bool $status;

    protected object | array $data = [];

    protected array $appended = [];

    protected ?string $message = null;

    protected int $code = 200;

    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    public function data($data): self
    {
        if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            $this->data = $data->toArray();
        } elseif ($data instanceof Model) {
            $this->data = $data->toArray();
        } elseif ($data instanceof Collection) {
            $this->data = $data->toArray();
        } else {
            $this->data = $data;
        }

        return $this;
    }

    public function appends(array $data): self
    {
        $this->appended = array_merge($this->appended, $data);
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
        $response = [
            'success' => $this->status,
            'message' => $this->message,
        ];

        if (!is_null($this->data)) {
            $response['data'] = $this->data;
        }

        if (!empty($this->appended)) {
            $response = array_merge($response, $this->appended);
        }

        return response()->json($response, $this->code);
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
