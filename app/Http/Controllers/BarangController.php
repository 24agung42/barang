<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index(): View
    {
        $title = 'Master Data Barang';
        return view('barang',[
            'title' => $title
        ]);
    }

    public function store(Request $request)
    {
        if ($request->act == 'save') {
            if ($request->kode == '') {
                $format = date('ymd');
                $getData = Barang::whereRaw("LEFT(kode,6) = ?",[$format])->max('kode');
                if (empty($getData)) { $kode = $format.'001'; }
                else {
                    $no = str_pad(strval(intval(substr($getData, 6))+1), 3, '0', STR_PAD_LEFT);
                    $kode = $format.$no;
                }

                $post = Barang::create([
                    'kode' => $kode,
                    'nama' => $request->nama,
                    'qty' => $request->qty,
                    'harga' => $request->harga
                ]);
            } else {
                $post = Barang::where('kode',base64_decode($request->kode))->update([
                    'nama' => $request->nama,
                    'qty' => $request->qty,
                    'harga' => $request->harga
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'data'    => $post  
            ]);
        } elseif ($request->act == 'delete') {
            $post = Barang::where('kode',base64_decode($request->kode))->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus!',
                'data'    => $post  
            ]);
        } elseif ($request->act == 'get') {
            return response()->json(['data' => Barang::distinct()->get()]);
        }
    }
}
