<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Categories::all();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        if (Categories::count()>=3){
            return response()->json([
                'status' => 'failed',
                'massage' => 'Limit is 3 categories'
            ]);
        }
        else{
            $category = new Categories();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return response()->json([
                'status' => 'success',
                'massage' => 'Category created',
                'data' => $category
            ]);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::find($id);

       //error
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        //success
        return response()->json([
            'message' => 'Category found',
            'data' => $category
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Categories::destroy($id);
        if(!$category){
            return response()->json(['message' => 'Category Was Failed To Deleted']);
        }
        return response()->json(['message' => 'Category Was Successfuly Deleted']);
    }
}
