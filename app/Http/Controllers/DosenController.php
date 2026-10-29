<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageUpload
    ) {}

    public function index(): View
    {
        $dosen = Dosen::orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('dosen.index', compact('dosen'));
    }

    public function create(): View
    {
        return view('dosen.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dosen', 'nidn'),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'bidang_keahlian' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->imageUpload->store(
                $request->file('foto'),
                'dosen'
            );
        }

        Dosen::create($validated);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dosen', 'nidn')->ignore($dosen->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'bidang_keahlian' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $fotoLama = $dosen->foto;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->imageUpload->store(
                $request->file('foto'),
                'dosen'
            );
        } else {
            unset($validated['foto']);
        }

        $dosen->update($validated);

        if ($request->hasFile('foto')) {
            $this->imageUpload->delete($fotoLama);
        }

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil diubah.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $foto = $dosen->foto;
        $dosen->delete();
        $this->imageUpload->delete($foto);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus.');
    }
}
