<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    protected $repository;

    public function __construct(Company $model)
    {
        $this->repository = $model;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = $this->repository->with('category')->paginate(12);
        return CompanyResource::collection($company);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $company = $this->repository->create($request->validated());
        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $appToken)
    {
        $company = $this->repository->with('category')->where('app_token', $appToken)->firstOrFail();
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, string $appToken)
    {
        $company = $this->repository->where('app_token', $appToken)->firstOrFail();
        $company->update($request->validated());
        return response()->json(['message' => 'updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $appToken): Response
    {
        $company = $this->repository->where('app_token', $appToken)->firstOrFail();
        $company->delete();
        return response()->json(['message' => 'deleted'], Response::HTTP_NO_CONTENT);
    }
}
