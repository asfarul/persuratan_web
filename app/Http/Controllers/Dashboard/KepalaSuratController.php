<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\KepalaSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KepalaSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $prefix = 'dashboard.kepalasurat.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataKepalaSurat = KepalaSurat::query();

            return datatables()->of($dataKepalaSurat)
                ->addColumn('nama_kop', function ($kopSurat) {
                    return $kopSurat->nama_kop;
                })
                ->addColumn('alamat_kop', function ($kopSurat) {
                    return $kopSurat->alamat_kop;
                })
                ->addColumn('nama_tujuan', function ($kopSurat) {
                    return $kopSurat->nama_tujuan;
                })
                ->addColumn('pembuat', function ($kopSurat) {
                    return $kopSurat->user->nama_ptgs;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->toJson();
        }
        return view($this->prefix . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view($this->prefix . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_kop' => 'required',
            'alamat_kop' => 'required',
            'nama_tujuan' => 'required',
        ];


        $this->validate($request, $rules);

        try {


            $kepalasurat = KepalaSurat::create([
                'nama_kop' => $request->nama_kop,
                'alamat_kop' => $request->alamat_kop,
                'nama_tujuan' => $request->nama_tujuan,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route($this->prefix . 'index')->with('success', 'Berhasil menambahkan item');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route($this->prefix . 'index')->with('error', 'Gagal menambahkan item');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kepalasurat = KepalaSurat::where('id', $id)->first();
        return view($this->prefix . 'show', compact('kepalasurat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kepalasurat = KepalaSurat::where('id', $id)->first();
        return view($this->prefix . 'edit', compact('kepalasurat'));
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
        $kepalasurat = KepalaSurat::where('id', $id)->first();

        $rules = [
            'nama_kop' => 'required',
            'alamat_kop' => 'required',
            'nama_tujuan' => 'required',
        ];



        $this->validate($request, $rules);

        try {

            $kepalasurat->update([
                'nama_kop' => $request->nama_kop ?? $kepalasurat->nama_kop,
                'alamat_kop' => $request->alamat_kop ?? $kepalasurat->alamat_kop,
                'nama_tujuan' => $request->nama_tujuan ?? $kepalasurat->nama_tujuan,
            ]);

            $kepalasurat->touch();

            return redirect()->route('dashboard.kepalasurat.index')->with('success', 'Berhasil mengupdate data');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.kepalasurat.index')->with('error', 'Gagal mengupdate data');
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
        $kepalasurat = KepalaSurat::findOrFail($id);
        if ($kepalasurat->delete()) {

            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }
}
