<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pegawai = $this->pegawai;
        return [
            'id' => $this->id,
            'nip' => $this->nip,    
            'nama_lengkap' => $pegawai->nama_lengkap,
            'gelar_depan' => $pegawai->gelar_depan,
            'gelar_belakang' => $pegawai->gelar_belakang,
            'no_hp' => $pegawai->no_hp,
            'jabatan' => $pegawai->jabatan->nama_jabatan,
            'email' => $this->email,
            'foto' => $pegawai->foto,
            'base_url' => asset('storage/'),
        ];
    }
}
