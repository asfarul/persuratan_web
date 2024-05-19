<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\MyEvent;
use App\Events\SmartTVEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\SukuBungaResource;
use App\Models\SukuBunga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SukuBungaController extends Controller
{

    protected $prefix = 'dashboard.sukubunga.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('sukubunga-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $sukubunga = SukuBunga::query();
            return datatables()->of($sukubunga)
                ->addColumn('judul', function ($item) {

                    return $item->judul;
                })
                ->addColumn('keterangan', function ($item) {

                    return $item->keterangan;
                })
                ->addColumn('status', function ($item) {
                    $status = '<div class="badge     badge-light-danger fw-bold my-1 mx-1">Nonaktif</div> ';
                    if ($item->is_active) {
                        $status = '<div class="badge badge-light-success fw-bold my-1 mx-1">Aktif</div> ';
                    }
                    return $status;
                })
                ->addColumn('last_updated_by', function ($item) {
                    // dd($item->user->pegawai);
                    return $item->user?->pegawai?->nama_lengkap ?? '-';
                })
                ->addColumn('kategori', function ($item) {
                    // dd($item->user->pegawai);
                    $kategori = 'PERSURATAN WEB';
                    if ($item->is_syariah) $kategori = 'PERSURATAN WEB SYARIAH';
                    return $kategori;
                })
                ->addColumn('updated_at', function ($item) {
                    $date = Carbon::parse($item->updated_at);
                    return $date->translatedFormat('d M Y, H:i:s');;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->rawColumns(['status'])
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
        abort_if(Gate::denies('sukubunga-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        abort_if(Gate::denies('sukubunga-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'judul' => 'required',
            'data' => 'required',
        ];

        $messages = [
            'judul.required' => 'Judul tidak boleh kosong',
            'data.required' => 'Data informasi tidak boleh kosong',
        ];

        $this->validate($request, $rules, $messages);

        try {

            // dd($request->all(), $request->data);
            $sukuBunga =  SukuBunga::create([
                'judul' => $request->judul,
                'keterangan' => $request->keterangan ?? '',
                'label' => $request->label ?? null,
                'data' => $this->setDataToJson($request->data),
                'is_active' => $request->is_active,
                'is_syariah' => $request->is_syariah,
                'created_by' => auth()->id(),
                'last_updated_by' => auth()->id(),
            ]);

            if ($sukuBunga) {
                $messageData = [
                    'category' => 'SUKUBUNGA',
                    'method' => 'CREATE',
                    'data' => new SukuBungaResource($sukuBunga),
                ];
                broadcast(new SmartTVEvent($messageData))->toOthers();
            }

            // event(new MyEvent($messageData));

            return redirect()->route($this->prefix . 'index')->with('success', 'Berhasil menambahkan item partner');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('sukubunga-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $sukubunga = SukuBunga::where('id', $id)->first();
        return view($this->prefix . 'edit', compact('sukubunga'));
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
        abort_if(Gate::denies('sukubunga-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'judul' => 'required',
            'label' => 'required',
            'data' => 'required',
        ];

        $messages = [
            'judul.required' => 'Judul tidak boleh kosong',
            'label.required' => 'Label tidak boleh kosong',
            'data.required' => 'Data informasi tidak boleh kosong',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $sukubunga = SukuBunga::where('id', $id)->first();

            $sukubunga->update([
                'judul' => $request->judul,
                'keterangan' => $request->keterangan ?? '',
                'label' => $request->label ?? null,
                'data' => $this->setDataToJson($request->data),
                'is_active' => $request->is_active,
                'is_syariah' => $request->is_syariah,
                'last_updated_by' => auth()->id(),
            ]);

            $sukubunga->touch();

            $messageData = [
                'category' => 'SUKUBUNGA',
                'method' => 'UPDATE',
                'data' => new SukuBungaResource($sukubunga),
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();

            return redirect()->route('dashboard.sukubunga.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.sukubunga.index')->with('error', 'Gagal mengupdate data');
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
        abort_if(Gate::denies('sukubunga-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $sukubunga = SukuBunga::findOrFail($id);
        if ($sukubunga->delete()) {
            $messageData = [
                'category' => 'SUKUBUNGA',
                'method' => 'DELETE',
                'data' => [
                    'id' => $id,
                ],
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();
            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }

    public function setDataToJson($value)
    {

        $properties = [];

        foreach ($value as $array_item) {
            if (!is_null($array_item['key'])) {
                $properties[] = $array_item;
            }
        }

        return $properties;
    }
}
