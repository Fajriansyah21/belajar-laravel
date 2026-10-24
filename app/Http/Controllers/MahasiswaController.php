<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        $mahasiswa = Mahasiswa::with('kelas')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function create(): View
    {
        $kelas = Kelas::all();

        return view('mahasiswa.create', compact('kelas'));
    }

    public function store(
        Request $request,
        ImageUploadService $imageUploadService
    ): RedirectResponse {
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim'),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'jurusan' => ['required', 'string', 'max:100'],
            'angkatan' => ['required', 'integer', 'min:1900', 'max:2200'],
            'email' => ['nullable', 'email', 'max:100'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        unset($validated['foto']);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $imageUploadService->store(
                $request->file('foto')
            );
        }

        Mahasiswa::create($validated);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        $kelas = Kelas::all();

        return view('mahasiswa.edit', compact('mahasiswa', 'kelas'));
    }

    public function update(
        Request $request,
        Mahasiswa $mahasiswa,
        ImageUploadService $imageUploadService
    ): RedirectResponse {
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'jurusan' => ['required', 'string', 'max:100'],
            'angkatan' => ['required', 'integer', 'min:1900', 'max:2200'],
            'email' => ['nullable', 'email', 'max:100'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        unset($validated['foto']);

        DB::transaction(function () use (
            $request,
            $mahasiswa,
            $validated,
            $imageUploadService
        ) {
            if ($request->hasFile('foto')) {
                $fotoLama = $mahasiswa->foto;

                $fotoBaru = $imageUploadService->store(
                    $request->file('foto')
                );

                $validated['foto'] = $fotoBaru;
                $mahasiswa->update($validated);

                DB::afterCommit(
                    fn () => $imageUploadService->delete($fotoLama)
                );
            } else {
                $mahasiswa->update($validated);
            }
        });

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diubah.');
    }

    public function destroy(
        Mahasiswa $mahasiswa,
        ImageUploadService $imageUploadService
    ): RedirectResponse {
        $fotoLama = $mahasiswa->foto;

        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->delete();
        });

        $imageUploadService->delete($fotoLama);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
