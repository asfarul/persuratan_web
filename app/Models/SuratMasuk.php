<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'surat_masuk';
    public $guarded = [];

    public function kepalaSurat(): BelongsTo
    {
        return $this->belongsTo(KepalaSurat::class, 'id_kop', 'id');
    }

    /**
     * Get the tandaTangan that owns the SuratKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tandaTangan(): BelongsTo
    {
        return $this->belongsTo(TandaTangan::class, 'id_tandatangan', 'id');
    }
}
