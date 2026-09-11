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
require_once __DIR__.'/bootstrap.php';
use WHMCS\Database\Capsule;
use Alphavision\BusinessOverview\Settings;
function alphavision_whmcs_business_overview_config(): array
{
    return ['name'=>'Alphavision WHMCS Business Overview',
        'description'=>'Indicadores comerciais, financeiros, operacionais e de suporte em um único painel.',
        'version'=>'1.0.5','author'=>'<a href="https://alphavision.com.br/" target="_blank" rel="noopener">Alphavision®</a>',
        'language'=>'portuguese-br','fields'=>\Alphavision\BusinessOverview\NativeConfig::fields()];
}
function alphavision_whmcs_business_overview_activate(): array
{
    try {
        if (!Capsule::schema()->hasTable(Settings::TABLE)) {
            Capsule::schema()->create(Settings::TABLE,static function($table){$table->unsignedInteger('id')->primary();$table->text('settings');});
        }
        if (!Capsule::table(Settings::TABLE)->where('id',1)->exists()) {
            Capsule::table(Settings::TABLE)->insert(['id'=>1,'settings'=>json_encode(Settings::defaults(),JSON_UNESCAPED_UNICODE)]);
        }
        return ['status'=>'success','description'=>'Ativado. Salve os perfis no Access Control e configure o Business Overview. Valores monetários começam ocultos.'];
    } catch (\Throwable $e) { return ['status'=>'error','description'=>'Não foi possível criar a tabela de configurações. Verifique as permissões do banco.']; }
}
function alphavision_whmcs_business_overview_deactivate(): array
{
    unset($_SESSION['avbo_cache']);
    return ['status'=>'success','description'=>'Desativado. Configurações preservadas para reativação. Consulte a documentação para remoção completa.'];
}
function alphavision_whmcs_business_overview_output(array $vars): void
{
    try { \Alphavision\BusinessOverview\Admin::render(); }
    catch (\Throwable $e) { echo '<div class="alert alert-danger">Business Overview indisponível. Confira a instalação dos arquivos e a tabela de configurações. Nenhum dado comercial foi alterado.</div>'; }
}
