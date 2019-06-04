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
     * @param Categories $category
     * @return Categories
     */
    public function show(Categories $category)
    {
        return $category;
    }

    /**
     * @param Request $request
     * @param Categories $category
     * @return Categories
     */
    public function update(Request $request, Categories $category)
    {
        $data = $request->all();
        $category->update($data);
        return $category;
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
