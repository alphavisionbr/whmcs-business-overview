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
if (!defined('WHMCS')) { exit('Acesso direto não permitido.'); }
define('AVBO_MODULE', 'alphavision_whmcs_business_overview');
foreach (['Catalog','Settings','Access','Metrics','View','NativeConfig','Admin','Widget'] as $class) {
    require_once __DIR__.'/src/'.$class.'.php';
}

require_once __DIR__.'/src/DashboardWidget.php';
