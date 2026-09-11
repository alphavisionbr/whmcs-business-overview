<?php
/**
 * Alphavision WHMCS Business Overview
 * @package AlphavisionWHMCSBusinessOverview
 * @author Alphavision®
 * @copyright Copyright (c) 2026 Alphavision®
 * @license MIT
 * @version 1.0.5
 * @link https://alphavision.com.br/
 */
declare(strict_types=1);
namespace Alphavision\BusinessOverview;
class Widget extends \WHMCS\Module\AbstractWidget
{
    protected $title='Alphavision WHMCS Business Overview';
    protected $description='Indicadores comerciais, financeiros, operacionais e de suporte.';
    protected $weight=10;
    protected $columns=2;
    protected $cache=false;
    protected $requiredPermission='';
    public function getData()
    {
        try {
            if (!Access::allowed() || !Settings::get()['widget']) { return ['state'=>'denied']; }
            return ['state'=>'ready','snapshot'=>Metrics::snapshot(true)];
        } catch (\Throwable $e) {
            return ['state'=>'error'];
        }
    }
    public function generateOutput($data)
    {
        try {
            if (!Access::allowed() || !Settings::get()['widget']) { return ''; }
            if (($data['state']??'')==='denied') { return ''; }
            if (($data['state']??'')!=='ready' || !is_array($data['snapshot']??null)) {
                return '<p class="avbo-notice">Business Overview indisponível. Consulte o diagnóstico do módulo.</p>';
            }
            return View::css().View::dashboard($data['snapshot'],true);
        } catch (\Throwable $e) {
            return '<p>Business Overview indisponível. Consulte o diagnóstico do módulo.</p>';
        }
    }
}
