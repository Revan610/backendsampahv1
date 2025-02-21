<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wastes;
use Illuminate\Support\Facades\Validator;

class WastesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wastes=Wastes::all();
        return response()->json([
            'status' => 'success',
            'data' => $wastes
        ]);
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
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:50',
            'price_per_kg'=>'required|numeric|min:0',
            'category_id'=>'required|exists:categories,id'
       ]);

       if ($validator->fails()){
        return response()->json([
            'status' => 'Failed',
            'errors' => $validator->errors()
        ], 422);
       }

        $wastes = new Wastes();
        $wastes->name = $request->name;
        $wastes->price_per_kg = $request->price_per_kg;
        $wastes->category_id = $request->category_id;
        $wastes->save();
        return response()->json([
            'status' => 'Success',
            'massage' => 'Wastes created',
            'data' => $wastes
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $wastes = Wastes::find($id);

       //error
        if (!$wastes) {
            return response()->json([
                'message' => 'Wastes not found'
            ], 404);
        }

        //success
        return response()->json([
            'message' => 'Wastes found',
            'data' => $wastes
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wastes $wastes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeAdmin();
        $waste = Wastes::find($id);
        if (!$waste) {
            return response()->json(['message' => 'Waste not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'price_per_kg' => 'required|numeric|min:0',
            'category_id' => 'required|exists:Categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data
        $waste->update([
            'name' => $request->name,
            'price_per_kg' => $request->price_per_kg,
            'category_id' => $request->category_id
        ]);

        return response()->json([
            'message' => 'Waste updated successfully',
            'data' => $waste
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeAdmin();
        $waste = Wastes::find($id);

        if (!$waste) {
            return response()->json(['message' => 'Wastes not found'], 404);
        }

        $waste->delete();
        return response()->json(['message' => 'Wastes successfully deleted']);
    }

    private function authorizeAdmin(){
        if (Auth::user()->role !== 'admin'){
            abort(403, 'AKSES DITOLAK. HANYA ADMIN YANG BOLEH MENGAKSES');
        }
    }
}
