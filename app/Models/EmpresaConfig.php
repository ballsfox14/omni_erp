<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaConfig extends Model
{
    use HasFactory;

    protected $table = 'empresa_configs';

    protected $fillable = [
        'nombre_empresa',
        'logo_path',
        'direccion',
        'telefono',
        'email',
        'website',
        'rnc',
        'footer_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener la URL del logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        
        return null;
    }

    /**
     * Obtener la instancia única de configuración
     */
    public static function getConfig()
    {
        $config = self::first();
        if (!$config) {
            $config = self::create(['nombre_empresa' => 'OmniERP']);
        }
        return $config;
    }
}