<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\KepalaSurat;
use App\Models\SuratMasuk;
use App\Models\TandaTangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    protected $prefix = 'dashboard.suratmasuk.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratMasuk::query();

            return datatables()->of($data)
                ->addColumn('tanggal', function ($row) {
                    return $row->tanggal;
                })
                ->addColumn('no_surat', function ($row) {
                    return $row->no_surat;
                })
                ->addColumn('asal_surat', function ($row) {
                    return $row->asal_surat;
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
            'asal_surat' => 'required',
            'disp1' => 'required',
            'disp2' => 'required',
            'image' => 'required',
            'id_tandatangan' => 'required',
        ];


        $this->validate($request, $rules);

        try {

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '.' . $file->extension();
                $filePath = 'images/surat/' . $fileName;
                $storedImage = Storage::putFileAs('public/images/surat', $file, $fileName);
            } else {
                $filePath = null;
            }

            $formattedDate = Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('Y-m-d');
            $data = SuratMasuk::create([
                'id_kop' => $request->id_kop,
                'tanggal' => $formattedDate,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'asal_surat' => $request->asal_surat,
                'disp1' => $request->disp1,
                'disp2' => $request->disp2,
                'image' => $filePath ?? '',
                'id_tandatangan' => $request->id_tandatangan,
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
        $data = SuratMasuk::findOrFail($id);
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
        $data = SuratMasuk::findOrFail($id);
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
        $suratMasuk = SuratMasuk::where('id', $id)->first();

        $rules = [
            'id_kop' => 'required',
            'tanggal' => 'required',
            'no_surat' => 'required',
            'perihal' => 'required',
            'asal_surat' => 'required',
            'disp1' => 'required',
            'disp2' => 'required',
            'image' => 'required',
            'id_tandatangan' => 'required',
        ];

        $this->validate($request, $rules);

        try {

            $formattedDate = Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('Y-m-d');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '.' . $file->extension();
                $filePath = 'images/surat/' . $fileName;
                $storedImage = Storage::putFileAs('public/images/surat', $file, $fileName);
            } else {
                $filePath = null;
            }

            $suratMasuk->update([
                'id_kop' => $request->id_kop,
                'tanggal' => $formattedDate,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'asal_surat' => $request->asal_surat,
                'disp1' => $request->disp1,
                'disp2' => $request->disp2,
                'image' => $filePath ?? $suratMasuk->image,
                'id_tandatangan' => $request->id_tandatangan,
            ]);

            $suratMasuk->touch();

            return redirect()->route('dashboard.suratmasuk.index')->with('success', 'Berhasil mengupdate data');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.suratmasuk.index')->with('error', 'Gagal mengupdate data');
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
        $suratMasuk = SuratMasuk::findOrFail($id);
        if ($suratMasuk->delete()) {

            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }
}
