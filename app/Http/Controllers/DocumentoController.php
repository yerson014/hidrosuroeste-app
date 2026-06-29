<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $documentos = Documento::with(['usuario'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('descripcion', 'ILIKE', '%' . $buscar . '%')
                             ->orWhere('entidad', 'ILIKE', '%' . $buscar . '%');
            })
            ->orderBy('documento_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('gestion.documentos.index', compact('documentos', 'buscar'));
    }

    public function create()
    {
        return view('gestion.documentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entidad'     => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'archivo'     => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB max
        ]);

        try {
            $data = $request->all();
            $data['usuario_id'] = Auth::id();
            $data['fecha'] = now();

            if ($request->hasFile('archivo')) {
                // Guarda el archivo en storage/app/public/documentos
                $path = $request->file('archivo')->store('documentos', 'public');
                $data['url_archivo'] = $path;
            }

            Documento::create($data);

            return redirect()->route('documentos.index')
                ->with('success', 'Documento adjuntado exitosamente.');

        } catch (\Exception $e) {
            Log::error("Error al crear documento: " . $e->getMessage());
            return back()->with('error', 'Error técnico: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $documento = Documento::findOrFail($id);
        return view('gestion.documentos.edit', compact('documento'));
    }

    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);
        
        $request->validate([
            'entidad'     => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'archivo'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('archivo')) {
                // Eliminar archivo anterior si existe
                if ($documento->url_archivo) {
                    Storage::disk('public')->delete($documento->url_archivo);
                }
                $path = $request->file('archivo')->store('documentos', 'public');
                $data['url_archivo'] = $path;
            }

            $documento->update($data);
            return redirect()->route('documentos.index')->with('success', 'Documento actualizado.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar documento: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            if ($documento->url_archivo) {
                Storage::disk('public')->delete($documento->url_archivo);
            }
            $documento->delete();
            return redirect()->route('documentos.index')->with('success', 'Documento eliminado.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }
}