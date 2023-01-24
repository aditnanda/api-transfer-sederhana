<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    //

    /**
     * @OA\Get(
     *      path="/api/bank/",
     *      operationId="allBank",
     *      tags={"Bank"},
     *      summary="All Bank",
     *      description="All Bank",
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function index(Request $request)
    {
        $sim = Bank::latest()->get();
        return response()->json($sim, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/bank",
     *      operationId="createBank",
     *      tags={"Bank"},
     *      summary="Create Bank",
     *      description="Create Bank",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                
     *                @OA\Property(
     *                    property="nama_bank",
     *                    type="string",
     *                    example="BCA"
     *                ),
     *                 
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }
        
        Bank::create([
            'nama_bank' => strtoupper($request->nama_bank),
        ]);

        return response()->json('Berhasil membuat data bank', 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/bank",
     *      operationId="deleteBank",
     *      tags={"Bank"},
     *      summary="Delete Bank",
     *      description="Delete Bank by id Bank",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id Bank",
     *         example="1"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function destroy(Request $request)
    {
        Bank::where('id', $request->id)->delete();
        return response()->json('Berhasil menghapus data bank', 200);
    }

    /**
     * @OA\Put(
     *      path="/api/bank",
     *      operationId="editBank",
     *      tags={"Bank"},
     *      summary="Edit Bank by id",
     *      description="Edit Bank by id",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id Bank",
     *         example="1"
     *      ),
     *      @OA\Parameter(
     *         name="nama_bank",
     *         in="query",
     *         example="BCA"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nama_bank' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }

        
        $bank = Bank::where('id', $request->id)->update([
            'nama_bank' => strtoupper($request->nama_bank)
        ]);

        if ($bank) {
            # code...
            return response()->json('Berhasil ubah data bank', 200);

        }

        return response()->json('Gagal ubah data bank', 200);

    }
}
