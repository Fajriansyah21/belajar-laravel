<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranJadwal extends Model
{
    use HasFactory;

    protected $table = 'lampiran_jadwal';

    protected $fillable = [
        'jadwal_kuliah_id',
        'path',
        'nama_asli',
    ];

    public function jadwalKuliah(): BelongsTo
    {
        return $this->belongsTo(JadwalKuliah::class);
    }
}
