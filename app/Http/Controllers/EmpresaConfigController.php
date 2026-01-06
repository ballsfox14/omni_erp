<?php

namespace App\Http\Controllers;

use App\Models\EmpresaConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaConfigController extends Controller
{
    public function edit()
    {
        $config = EmpresaConfig::getConfig();
        return view('empresa-config.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $config = EmpresaConfig::getConfig();
        
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'direccion' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'rnc' => 'nullable|string|max:50',
            'footer_text' => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'nombre_empresa',
            'direccion',
            'telefono',
            'email',
            'website',
            'rnc',
            'footer_text',
        ]);

        // Manejar la subida del logo
        if ($request->hasFile('logo')) {
            // Eliminar el logo anterior si existe
            if ($config->logo_path && Storage::exists('public/' . $config->logo_path)) {
                Storage::delete('public/' . $config->logo_path);
            }

            // Guardar el nuevo logo
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        $config->update($data);

        return redirect()->route('empresa-config.edit')
            ->with('success', 'Configuración de empresa actualizada exitosamente.');
    }

    /**
     * Eliminar el logo
     */
    public function deleteLogo()
    {
        $config = EmpresaConfig::getConfig();
        
        if ($config->logo_path && Storage::exists('public/' . $config->logo_path)) {
            Storage::delete('public/' . $config->logo_path);
            $config->update(['logo_path' => null]);
        }

        return redirect()->route('empresa-config.edit')
            ->with('success', 'Logo eliminado exitosamente.');
    }
}