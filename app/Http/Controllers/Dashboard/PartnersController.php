<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\SmartTVEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PartnersController extends Controller
{



    protected $prefix = 'dashboard.partners.';

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
        abort_if(Gate::denies('partners-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $partners = Partner::query();
            if ($request->has('status') && $request->status != '') {
                $partners->where('is_active', $request->status);
            }
            return datatables()->of($partners)
                ->addColumn('nama', function ($partner) {

                    return $partner->nama;
                })
                ->addColumn('logo', function ($partner) {
                    $logo = ' <img src="' . asset('storage/' . $partner->logo) . '"
                    class="w-75px align-self-center">';
                    return $logo;
                })
                ->addColumn('status', function ($partner) {
                    $status = '<div class="badge badge-light-danger fw-bold my-1 mx-1">Nonaktif</div> ';
                    if ($partner->is_active) {
                        $status = '<div class="badge badge-light-success fw-bold my-1 mx-1">Aktif</div> ';
                    }
                    return $status;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->rawColumns(['status', 'logo'])
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
        abort_if(Gate::denies('partners-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('partners-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'nama' => 'required',
            'logo' => 'required|max:2000|mimes:jpg,png,webp,webm',
        ];

        $messages = [
            'nama.required' => 'Nama tidak boleh kosong',
            'max' => 'Ukuran gambar tidak boleh lebih dari 20MB',
            'mimes' => 'Format file harus: jpg, jpeg, png, webp, webm'
        ];

        $this->validate($request, $rules, $messages);

        try {
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '.' . $file->extension();
                $filePath = 'images/partners/' . $fileName;
                $storedImage = Storage::putFileAs('public/images/partners', $file, $fileName);
            } else {
                $filePath = null;
            }

            $partner = Partner::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'logo' => $filePath,
                'is_active' => $request->is_active == 1 ? true : false,
                'created_by' => auth()->id(),
            ]);

            if ($partner) {
                $messageData = [
                    'category' => 'PARTNER',
                    'method' => 'CREATE',
                    'data' => new PartnerResource($partner),
                ];
                broadcast(new SmartTVEvent($messageData))->toOthers();
            }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('partners-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $partner = Partner::where('id', $id)->first();
        return view($this->prefix . 'edit', compact('partner'));
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
        abort_if(Gate::denies('partners-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $partner = Partner::where('id', $id)->first();

        $rules = [
            'nama' => 'required',
            'logo' => 'nullable|max:2000|mimes:jpg,png,webp,webm',
        ];

        $messages = [
            'nama.required' => 'Nama tidak boleh kosong',
            'max' => 'Ukuran gambar tidak boleh lebih dari 20MB',
            'mimes' => 'Format file harus: jpg, jpeg, png, webp, webm'
        ];
        $this->validate($request, $rules, $messages);

        try {
            // jika ada logo
            if ($request->hasFile('logo')) {
                if ($partner->logo && Storage::exists($partner->logo)) {
                    Storage::delete($partner->logo);
                }

                $image = $request->file('logo');
                $imageName = time() . '.' . $image->extension();
                $imagePath = 'images/partners/' . $imageName;
                $storedImage = Storage::putFileAs('public/images/partners', $image, $imageName);
            } else {
                $imagePath = $partner->logo;
            }


            $partner->update([
                'nama' => $request->nama ?? $partner->nama,
                'keterangan' => $request->keterangan ?? $partner->deskripsi,
                'logo' => $imagePath,
                'is_active' => $request->is_active == 1 ? true : false,
                'last_updated_by' => auth()->id(),
            ]);

            $partner->touch();

            $messageData = [
                'category' => 'PARTNER',
                'method' => 'UPDATE',
                'data' => new PartnerResource($partner),
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();

            return redirect()->route('dashboard.partners.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.partners.index')->with('error', 'Gagal mengupdate data');
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
        abort_if(Gate::denies('partners-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $partner = Partner::findOrFail($id);
        if ($partner->delete()) {
            $messageData = [
                'category' => 'PARTNER',
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
}
