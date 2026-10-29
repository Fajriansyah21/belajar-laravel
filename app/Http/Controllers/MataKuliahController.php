<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $mataKuliah = MataKuliah::orderBy('id')->get();

        return view('mata_kuliah.index', compact('mataKuliah'));
    }

    public function create(): View
    {
        return view('mata_kuliah.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('mata_kuliah', 'kode')],
            'nama_mata_kuliah' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'modul' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'modul.file' => 'Modul harus berupa file.',
            'modul.mimes' => 'File modul harus berformat PDF.',
            'modul.max' => 'Ukuran modul maksimal 5 MB.',
        ]);

        if ($request->hasFile('modul')) {
            $file = $request->file('modul');
            $path = 'modul/' . Str::uuid() . '.pdf';
            Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
            $validated['modul'] = $path;
        }

        MataKuliah::create($validated);

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mata_kuliah): View
    {
        return view('mata_kuliah.edit', compact('mata_kuliah'));
    }

    public function update(Request $request, MataKuliah $mata_kuliah): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('mata_kuliah', 'kode')->ignore($mata_kuliah->id)],
            'nama_mata_kuliah' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'modul' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'modul.file' => 'Modul harus berupa file.',
            'modul.mimes' => 'File modul harus berformat PDF.',
            'modul.max' => 'Ukuran modul maksimal 5 MB.',
        ]);

        $modulLama = $mata_kuliah->modul;

        if ($request->hasFile('modul')) {
            $file = $request->file('modul');
            $path = 'modul/' . Str::uuid() . '.pdf';
            Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
            $validated['modul'] = $path;
        } else {
            unset($validated['modul']);
        }

        $mata_kuliah->update($validated);

        if ($request->hasFile('modul') && $modulLama) {
            Storage::disk('public')->delete($modulLama);
        }

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil diubah.');
    }

    public function destroy(MataKuliah $mata_kuliah): RedirectResponse
    {
        $modul = $mata_kuliah->modul;
        $mata_kuliah->delete();

        if ($modul) {
            Storage::disk('public')->delete($modul);
        }

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus.');
    }
}
