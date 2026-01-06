<?php

namespace App\View\Composers;

use App\Models\EmpresaConfig;
use Illuminate\View\View;

class EmpresaConfigComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $config = EmpresaConfig::getConfig();
        $view->with('empresaConfig', $config);
    }
}