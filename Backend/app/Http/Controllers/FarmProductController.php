<?php

namespace App\Http\Controllers;

use App\Models\FarmProduct;
use App\Http\Requests\StoreFarmProductRequest;
use App\Http\Requests\UpdateFarmProductRequest;

class FarmProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFarmProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmProduct $farmProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmProduct $farmProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFarmProductRequest $request, FarmProduct $farmProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmProduct $farmProduct)
    {
        //
    }
}
