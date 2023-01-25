<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use Illuminate\Support\Carbon;

use App\KegiatanLaporan;
use App\User;

class KegiatanLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        try {
            if($user->role == 'operator') {
                $data = KegiatanLaporan::where('id_users',$user->id)->orderBy('kegiatan_laporans.id','desc')->get();
            } else {
                $data = KegiatanLaporan::with('users')->orderBy('kegiatan_laporans.id','desc')->get();
            }

            return response()->json(['status' => "show", "message" => "Menampilkan Data" , 'data' => $data]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $date = $request->tanggal;
        $nort = $request->id_rt;
        $fixed = date('Y-m-d', strtotime(substr($date,0,10)));

        $requestData = $request->all();
        if($date) {
            $requestData['tanggal'] = $fixed;
        }
        if($nort) {
            $getuser = User::where('id_rt',$nort)->first();
            $requestData['id_users'] = $getuser->id;
        }
        $now = Carbon::now();
        $bulan = Carbon::createFromFormat('Y-m-d', $fixed)->format('m');
        $tahun = Carbon::createFromFormat('Y-m-d', $fixed)->format('Y');
//         $requestData['id_users'] = $user->id;
        $requestData['bulan'] = $bulan;
        $requestData['tahun'] = $tahun;
 
        try {
            if($user->role == 'admin') {
                KegiatanLaporan::create($requestData);
                
                return response()->json(["status" => "success", "message" => "Berhasil Menambahkan Data"]);
            } else {
                if($bulan !== $now->format('m')) {
                    return response()->json(["status" => "error", "message" => "Gagal Menambahkan Data, Tanggal Kadaluarsa"]);
                } else {

                    KegiatanLaporan::create($requestData);

                    return response()->json(["status" => "success", "message" => "Berhasil Menambahkan Data"]);
                }
            }

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('pages/kegiatan/indexlaporan');
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
        $user = Auth::user();
        
        $date = $request->tanggal;
        $nort = $request->id_rt;
        $fixed = date('Y-m-d', strtotime(substr($date,0,10)));
        
        $requestData = $request->all();
        
        if($nort) {
            $getuser = User::where('id_rt',$nort)->first();
            $requestData['id_users'] = $getuser->id;
        }
        
        $now = Carbon::now();
        if($date) {
            $requestData['tanggal'] = $fixed;
            $hari = Carbon::createFromFormat('Y-m-d', $fixed)->format('d');
            $bulan = Carbon::createFromFormat('Y-m-d', $fixed)->format('m');
            $tahun = Carbon::createFromFormat('Y-m-d', $fixed)->format('Y');
            $requestData['bulan'] = $bulan;
            $requestData['tahun'] = $tahun;
        }
        
        try {
       
            $data = KegiatanLaporan::findOrFail($id);
            if($user->role == 'admin') {
                $data->update($requestData);
                return response()->json(["status" => "success", "message" => "Berhasil Ubah Data"]);
            } else {
                if($now->format('m') !== $data->bulan) {
                    return response()->json(["status" => "error", "message" => "Gagal Ubah Data, Tanggal Kadaluarsa"]);
                } else {
                    if(!empty($date)) {
                        if($bulan !== $now->format('m')){
                            return response()->json(["status" => "error", "message" => "Gagal Ubah Data, Tanggal Kadaluarsa"]);
                        } else {
                            $data->update($requestData);
                            return response()->json(["status" => "success", "message" => "Berhasil Ubah Data"]);
                        }
                    }
                    $data->update($requestData);
                    return response()->json(["status" => "success", "message" => "Berhasil Ubah Data"]);            
                }
            }
              


        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $now = Carbon::now();
        try {
            $data = KegiatanLaporan::find($id);
            if($user->role == 'admin') {
                $data->delete();
                return response()->json(["status" => "success", "message" => "Berhasil Hapus Data"]);
            } else {
                if($now->format('m') !== $data->bulan) {
                    return response()->json(["status" => "error", "message" => "Gagal Hapus Data, Tanggal Kadaluarsa"]);
                } else {  
                    $data->delete();
                    return response()->json(["status" => "success", "message" => "Berhasil Hapus Data"]);
                }
            }


        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function cetakkegiatan($bulan,$tahun,$rt)
    {
        $bulans = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember',''];
        $getrt = DB::table('rt')->where('nomor_rt',$rt)->first();

        
        
        if($getrt) {
            $getuser = DB::table('users')->where('id_rt',$getrt->nomor_rt)->first();
            $getadmin = DB::table('users')->where('role','admin')->first();
        } else {
            return 404;
        }

        if($getuser) {            
            $data = KegiatanLaporan::with('kegiatans')->where('bulan',(int)$bulan)
            ->where('tahun',$tahun)
            ->where('id_users',$getuser->id)
            ->orderBy('tanggal')
            ->get();
            
            // return $data;

            $pdf = PDF::loadview('pdfkegiatan',[
                'data'=>$data,
                'rt'=>$getrt,
                'namart'=>$getuser->nama_lengkap,
                'namaadmin'=>$getadmin->nama_lengkap,
                'bulan'=>$bulans[$bulan],
            ])->setPaper('a4', 'landscape');
            return $pdf->stream();

            // return view('pdfkegiatan',[
            //     'data'=>$data,
            //     'rt'=>$getrt,
            //     'namart'=>$getuser->nama_lengkap,
            //     'namaadmin'=>$getadmin->nama_lengkap,
            //     'bulan'=>$bulans[$bulan],
            // ]);

        } else {
            return 404;
        }


       
    }
}
