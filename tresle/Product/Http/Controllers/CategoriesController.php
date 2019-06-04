<?php

namespace Tresle\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Tresle\Product\Model\Categories;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class CategoriesController extends Controller
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Categories[]
     */
    public function index()
    {
        return Categories::all();
    }


    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return Categories::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
