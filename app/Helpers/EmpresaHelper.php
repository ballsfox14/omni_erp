<?php

namespace App\Helpers;

use App\Models\EmpresaConfig;

class EmpresaHelper
{
    /**
     * Obtener la configuración de la empresa
     */
    public static function getConfig()
    {
        return EmpresaConfig::getConfig();
    }

    /**
     * Obtener el logo de la empresa
     */
    public static function getLogoUrl()
    {
        $config = self::getConfig();
        return $config->logo_url;
    }

    /**
     * Obtener el nombre de la empresa
     */
    public static function getNombreEmpresa()
    {
        $config = self::getConfig();
        return $config->nombre_empresa;
    }

    /**
     * Verificar si existe logo
     */
    public static function hasLogo()
    {
        $config = self::getConfig();
        return !empty($config->logo_path);
    }

    /**
     * Obtener información completa para reportes
     */
    public static function getInfoReporte()
    {
        $config = self::getConfig();
        return [
            'nombre_empresa' => $config->nombre_empresa,
            'logo_url' => $config->logo_url,
            'direccion' => $config->direccion,
            'telefono' => $config->telefono,
            'email' => $config->email,
            'website' => $config->website,
            'rnc' => $config->rnc,
            'footer_text' => $config->footer_text,
        ];
    }
}