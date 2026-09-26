<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $mataKuliah = MataKuliah::all();

        return view('mata_kuliah.index', compact('mataKuliah'));
    }

    public function create(): View
    {
        return view('mata_kuliah.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
            'nama_mk' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        MataKuliah::create($validated);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        return view('mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(
        Request $request,
        MataKuliah $mataKuliah
    ): RedirectResponse {
        $validated = $request->validate([
            'kode_mk' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliah', 'kode_mk')
                    ->ignore($mataKuliah->id),
            ],
            'nama_mk' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $mataKuliah->update($validated);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil diubah.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->delete();

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus.');
    }
}