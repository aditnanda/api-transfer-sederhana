<?php

namespace App\Http\Controllers;

use App\Models\RekeningAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekeningAdminController extends Controller
{
    //
    /**
     * @OA\Get(
     *      path="/api/rekening-admin",
     *      operationId="allRekeningAdmin",
     *      tags={"RekeningAdmin"},
     *      summary="All RekeningAdmin",
     *      description="All RekeningAdmin",
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function index(Request $request)
    {
        $sim = RekeningAdmin::latest()->get();
        return response()->json($sim, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/rekening-admin",
     *      operationId="createRekeningAdmin",
     *      tags={"RekeningAdmin"},
     *      summary="Create RekeningAdmin",
     *      description="Create RekeningAdmin",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                
     *                @OA\Property(
     *                    property="nama_bank",
     *                    type="string",
     *                    example="BNI"
     *                ),
     *                @OA\Property(
     *                    property="atas_nama",
     *                    type="string",
     *                    example="Bos COD"
     *                ),
     *                @OA\Property(
     *                    property="no_rekening",
     *                    type="string",
     *                    example="98301920232"
     *                ),
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
            'atas_nama' => 'required|max:100',
            'no_rekening' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }
        
        RekeningAdmin::create([
            'nama_bank' => strtoupper($request->nama_bank),
            'atas_nama' => $request->atas_nama,
            'no_rekening' => $request->no_rekening,
        ]);

        return response()->json('Berhasil membuat data rekening admin', 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/rekening-admin",
     *      operationId="deleteRekeningAdmin",
     *      tags={"RekeningAdmin"},
     *      summary="Delete RekeningAdmin",
     *      description="Delete RekeningAdmin by id RekeningAdmin",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id RekeningAdmin",
     *         example="1"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function destroy(Request $request)
    {
        RekeningAdmin::where('id', $request->id)->delete();
        return response()->json('Berhasil menghapus data rekening admin', 200);
    }

    /**
     * @OA\Put(
     *      path="/api/rekening-admin",
     *      operationId="editRekeningAdmin",
     *      tags={"RekeningAdmin"},
     *      summary="Edit RekeningAdmin by id",
     *      description="Edit RekeningAdmin by id",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id RekeningAdmin",
     *         example="1"
     *      ),
     *      @OA\Parameter(
     *         name="nama_bank",
     *         in="query",
     *         example="BNI"
     *      ),
     *      @OA\Parameter(
     *         name="atas_nama",
     *         in="query",
     *         example="Bos COD"
     *      ),
     *      @OA\Parameter(
     *         name="no_rekening",
     *         in="query",
     *         example="98301920232"
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
            'atas_nama' => 'required|max:100',
            'no_rekening' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }

        
        $rekening_admin = RekeningAdmin::where('id', $request->id)->update([
            'nama_bank' => strtoupper($request->nama_bank),
            'atas_nama' => $request->atas_nama,
            'no_rekening' => $request->no_rekening,
        ]);

        if ($rekening_admin) {
            # code...
            return response()->json('Berhasil ubah data rekening admin', 200);

        }

        return response()->json('Gagal ubah data rekening admin', 200);

    }
}
