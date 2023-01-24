<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningAdmin;
use App\Models\TransaksiRequest;
use App\Models\TransaksiTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiTransferController extends Controller
{
    //

    /**
     * @OA\Get(
     *      path="/api/transfer",
     *      operationId="allTransfer",
     *      tags={"Transfer"},
     *      summary="All Transfer",
     *      description="All Transfer",
     *      @OA\Response(response=200, description="Successful operation"),
     *      security={{"bearerAuth" : {}}}
     * )
     */
    public function index(Request $request)
    {
        $sim = TransaksiRequest::with(['user','transfer'])->latest()->get();
        return response()->json($sim, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/transfer",
     *      operationId="createTransfer",
     *      tags={"Transfer"},
     *      summary="Create Transfer",
     *      description="Create Transfer",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                
     *                @OA\Property(
     *                    property="nilai_transfer",
     *                    type="string",
     *                    example="50000"
     *                ),
     *                @OA\Property(
     *                    property="bank_tujuan",
     *                    type="string",
     *                    example="BCA"
     *                ),
     *                @OA\Property(
     *                    property="rekening_tujuan",
     *                    type="string",
     *                    example="98301920232"
     *                ),
     *                @OA\Property(
     *                    property="atasnama_tujuan",
     *                    type="string",
     *                    example="Fulan"
     *                ),
     *                @OA\Property(
     *                    property="bank_pengirim",
     *                    type="string",
     *                    example="BNI"
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
            'nilai_transfer' => 'required|max:15',
            'bank_tujuan' => 'required|max:100',
            'rekening_tujuan' => 'required|max:100',
            'atasnama_tujuan' => 'required|max:100',
            'bank_pengirim' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            
            $valid = array(
                'error' => $validator->errors(),
            );

            return response()->json($valid, 401);
        }

        // cek apakah ada bank
        $bank_tujuan = Bank::where('nama_bank',strtoupper($request->bank_tujuan))->first();
        if (!$bank_tujuan) {
            # code...
            return response()->json('Bank Tujuan tidak terdaftar dalam sistem', 200);
        }

        $bank_pengirim = Bank::where('nama_bank',strtoupper($request->bank_pengirim))->first();
        if (!$bank_pengirim) {
            # code...
            return response()->json('Bank Pengirim tidak terdaftar dalam sistem', 200);
        }

        $value = $request->all();
        $value['user_id'] = auth()->user()->id;
        $transaksi_request = TransaksiRequest::create($value);

        $num = TransaksiTransfer::whereDate('created_at',date('Y-m-d'))->count();

        // pembuatan id transaksi
        $id_transaksi = 'TF'.date('ymd').sprintf("%05d", $num + 1);
        $uniq = rand(111,999);

        // rekening admin
        $rek_admin = RekeningAdmin::first();

        if ($transaksi_request) {
            # code...
            $transaksi = TransaksiTransfer::create([
                'transaksi_request_id' => $transaksi_request->id,
                'id_transaksi' => $id_transaksi,
                'nilai_transfer' => $request->nilai_transfer,
                'kode_unik' => $uniq,
                'biaya_admin' => 0,
                'total_transfer' => $request->nilai_transfer + $uniq,
                'bank_perantara' => $rek_admin->nama_bank,
                'rekening_perantara' => $rek_admin->no_rekening,
                'berlaku_hingga' => date('Y-m-d H:i:s',strtotime('+3 days',strtotime(date('Y-m-d H:i:s')))),
            ]);

            unset($transaksi->id);
            unset($transaksi->created_at);
            unset($transaksi->updated_at);
            return response()->json($transaksi, 200);

        }

        return response()->json('Gagal membuat transfer', 200);
    }
}
