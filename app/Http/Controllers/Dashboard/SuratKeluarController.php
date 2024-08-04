<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\KepalaSurat;
use App\Models\SuratKeluar;
use App\Models\TandaTangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuratKeluarController extends Controller
{
    protected $prefix = 'dashboard.suratkeluar.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratKeluar::query();

            return datatables()->of($data)
                ->addColumn('tanggal', function ($row) {
                    return $row->tanggal;
                })
                ->addColumn('no_surat', function ($row) {
                    return $row->no_surat;
                })
                ->addColumn('kop_detail', function ($row) {

                    $detail = '<div class="d-flex flex-column">
        <a href="' . route('dashboard.kepalasurat.index', $row->kepalaSurat->id) . '"
            class="text-gray-800 text-hover-primary mb-1">' .  $row->kepalaSurat->nama_kop . '</a>
    </div>';

                    $output = '<div class="d-flex align-items-center">' . $detail . '</div>';
                    return $output;
                })

                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->rawColumns(['kop_detail'])
                ->toJson();
        }
        return view($this->prefix . 'index');
    }

    public function create()
    {
        $tandaTangan = TandaTangan::all();
        $kopSurat = KepalaSurat::all();
        return view($this->prefix . 'create', compact(['tandaTangan', 'kopSurat']));
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
            'id_kop' => 'required',
            'tanggal' => 'required',
            'no_surat' => 'required',
            'perihal' => 'required',
            'tujuan' => 'required',
            'isi_surat' => 'required',
            'id_tandatangan' => 'required',
        ];


        $this->validate($request, $rules);

        try {

            $formattedDate = Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('Y-m-d');
            $data = SuratKeluar::create([
                'id_kop' => $request->id_kop,
                'tanggal' => $formattedDate,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'tujuan' => $request->tujuan,
                'isi_surat' => $request->isi_surat,
                'id_tandatangan' => $request->id_tandatangan,
                'user_id' =>  auth()->id(),
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
        $data = SuratKeluar::findOrFail($id);
        return view($this->prefix . 'show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SuratKeluar::findOrFail($id);
        return view($this->prefix . 'edit', compact('data'));
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
        $suratKeluar = SuratKeluar::where('id', $id)->first();

        $rules = [
            'id_kop' => 'required',
            'tanggal' => 'required',
            'no_surat' => 'required',
            'perihal' => 'required',
            'tujuan' => 'required',
            'isi_surat' => 'required',
            'id_tandatangan' => 'required',
        ];

        $this->validate($request, $rules);

        try {

            $formattedDate = Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('Y-m-d');

            $suratKeluar->update([
                'id_kop' => $request->id_kop,
                'tanggal' => $formattedDate,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'tujuan' => $request->tujuan,
                'isi_surat' => $request->isi_surat,
                'id_tandatangan' => $request->id_tandatangan,
            ]);

            $suratKeluar->touch();

            return redirect()->route('dashboard.suratkeluar.index')->with('success', 'Berhasil mengupdate data');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.suratkeluar.index')->with('error', 'Gagal mengupdate data');
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
        $suratKeluar = SuratKeluar::findOrFail($id);
        if ($suratKeluar->delete()) {

            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }
}
