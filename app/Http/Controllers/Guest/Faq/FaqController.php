<?php

namespace App\Http\Controllers\Guest\Faq;

use App\Models\Faq\Faq;
use App\Actions\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = Faq::query()->paginate($request->query('per_page', 10));
        return Response::success()->data($query)->message('Succesfully');
    }
}
