<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(private ProductCatalogService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'animal' => $request->query('animal', 'all'),
            'type' => $request->query('type', 'all'),
            'search' => $request->query('search', ''),
            'sort' => $request->query('sort', 'default'),
            'page' => max(1, (int) $request->query('page', 1)),
        ];

        return response()->json($this->service->fetch($filters));
    }
}
