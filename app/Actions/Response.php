<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class Response
{
    protected bool $status;
    protected array $data = [];
    protected ?string $message = null;
    protected int $code = 200;
    protected ?array $pagination = null;

    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    public function data($data): self
    {
        if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            $this->data = $data->items();
            $this->pagination = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => method_exists($data, 'total') ? $data->total() : null,
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ];
        } elseif ($data instanceof Model) {
            $this->data = $data->toArray();
        } else {
            $this->data = $data;
        }
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

        if (!empty($this->data)) {
            $response['data'] = $this->data;
        }

        if (!empty($this->pagination)) {
            $response['pagination'] = $this->pagination;
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
