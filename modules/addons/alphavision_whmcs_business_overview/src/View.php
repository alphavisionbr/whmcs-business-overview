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
final class View
{
    public static function e(mixed $s): string { return htmlspecialchars((string)$s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
    public static function url(string $page='overview'): string { return 'addonmodules.php?module='.AVBO_MODULE.'&page='.rawurlencode($page); }
    public static function css(): string { return '<style>'.file_get_contents(dirname(__DIR__).'/assets/admin.css').'</style>'; }
    public static function zero(mixed $value): bool
    {
        if ($value===null) { return false; }
        if (is_array($value)) {
            foreach ($value as $v) { if ((float)$v!=0) { return false; } }
            return true;
        }
        return (float)$value==0;
    }
    public static function number(mixed $value): string
    {
        if ($value===null) { return '<span class="avbo-unavailable">Indisponível</span>'; }
        if (is_array($value)) {
            if (!$value) { return '<span>0,00</span>'; }
            $out='';
            foreach ($value as $code=>$amount) { $out.='<span class="avbo-money"><small>'.self::e($code).'</small> '.number_format((float)$amount,2,',','.').'</span>'; }
            return $out;
        }
        return number_format((float)$value,0,',','.');
    }
    public static function dashboard(array $data, bool $widget=false): string
    {
        if (!$data || !Access::allowed()) { return ''; }
        $s=$data['settings'];$defs=Catalog::all();$groups='';$count=0;
        foreach (Catalog::groups() as $g=>$title) {
            $rows='';
            foreach ($defs as $key=>$def) {
                if ($def['group']!==$g || !array_key_exists($key,$data['values']) || !Access::metric($def,$s)) { continue; }
                $value=$data['values'][$key];
                if ($s['hide_zero'] && self::zero($value)) { continue; }
                $attention=$s['attention'] && $def['attention'] && $value!==null && !self::zero($value);
                $help=$def['help'];
                $label=$def['label'];
                if ($key==='domains_expiring') { $help='Hoje até +'.$s['days'].' dias. '.$help; }
                $url=$def['url'];
                if (str_starts_with($key,'income_') && !Access::can('List Transactions')) { $url=''; }
                $tag=$url!==''?'a':'div';
                $rows.='<'.$tag.($url!==''?' href="'.self::e($url).'"':'').' class="avbo-row'.($attention?' avbo-attention':'').($value===null?' avbo-failed':'').'" title="'.self::e($help).'">';
                $rows.='<span class="avbo-label">'.self::e($label).($key==='domains_expiring'?' ('.$s['days'].' dias)':'').'</span><strong>'.self::number($value).'</strong>';
                { $rows.='<small class="avbo-definition">'.self::e($help).'</small>'; }
                $rows.='</'.$tag.'>';$count++;
            }
            if ($rows!=='') { $groups.='<section class="avbo-group avbo-group-'.$g.'"><h3>'.self::e($title).'</h3>'.'<div class="avbo-items">'.$rows.'</div></section>'; }
        }
        $out='<div class="avbo avbo-dashboard '.($widget?'avbo-widget avbo-layout-'.$s['layout']:'avbo-overview').'">';
        if (!$widget) { $out.='<div class="avbo-period"><span>Hoje: '.date('d/m/Y').' · Mês: '.date('m/Y').'</span><small>'.self::e(date_default_timezone_get()).' (GMT'.date('P').')</small></div>'; }

        if ($data['errors']) { $out.='<p class="avbo-notice" role="status">Alguns indicadores estão indisponíveis. Consulte o diagnóstico.</p>'; }
        $out.=$count?'<div class="avbo-grid">'.$groups.'</div>':'<p class="avbo-empty">Nenhum indicador visível. Confira a seleção, as permissões e a opção de ocultar valores zero.</p>';
        if (!$widget) { $out.='<footer class="avbo-footer"><small>Atualizado às '.date('H:i:s',$data['at']).' · Cache: '.(int)$s['ttl'].'s</small></footer>'; }
        $out.='</div>';

        return $out;
    }
}
