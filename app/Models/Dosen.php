<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';


    protected $fillable = [
        'nidn',
        'nama',
        'bidang_keahlian',
        'email',
        'no_telepon',
        'foto',
    ];

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto
            ? Storage::disk('public')->url($this->foto)
            : null;
    }

}