<?php
/** Alphavision WHMCS Business Overview · v1.0.5 · MIT */
declare(strict_types=1);
namespace Alphavision\BusinessOverview;
final class NativeConfig
{
    public static function fields(): array
    {
        try { $s=Settings::get(); } catch (\Throwable $e) { $s=Settings::defaults(); }
        $help=static fn(string $text): string=>'<small class="avbo-native-help">'.View::e($text).'</small>';
        $section=static fn(string $title,string $text): array=>[
            'FriendlyName'=>'<span class="avbo-native-section">'.View::e($title).'</span>',
            'Type'=>'text','Size'=>'1','Default'=>'','Description'=>$help($text)
        ];
        $fields=[
            'bo_section_widget'=>$section('Widget administrativo','Configure a apresentação dos indicadores no dashboard. A atualização ocorre a cada carregamento.'),
            'bo_widget'=>['FriendlyName'=>'Exibir widget','Type'=>'yesno','Default'=>$s['widget']?'on':'','Description'=>'Habilitar no dashboard.'.$help('A visibilidade também depende do Access Control e das opções pessoais do dashboard.')],
            'bo_layout'=>['FriendlyName'=>'Layout do widget','Type'=>'dropdown','Options'=>['compact'=>'Compacto','full'=>'Com explicações'],'Default'=>$s['layout'],'Description'=>$help('Compacto oculta as descrições e reduz a altura. A visão geral do addon sempre exibe as explicações.')],
            'bo_section_display'=>$section('Apresentação dos indicadores','Estas opções são compartilhadas pelo widget e pela visão geral do addon.'),
            'bo_hide_zero'=>['FriendlyName'=>'Indicadores zerados','Type'=>'yesno','Default'=>$s['hide_zero']?'on':'','Description'=>'Ocultar quando zero.'.$help('Indicadores indisponíveis continuam visíveis.')],
            'bo_attention'=>['FriendlyName'=>'Sinalização de atenção','Type'=>'yesno','Default'=>$s['attention']?'on':'','Description'=>'Destacar pendências.'.$help('Realça atrasos e pendências com valores diferentes de zero.')],
            'bo_days'=>['FriendlyName'=>'Domínios próximos do vencimento','Type'=>'dropdown','Options'=>['7'=>'7 dias','15'=>'15 dias','30'=>'30 dias'],'Default'=>(string)$s['days'],'Description'=>$help('Considera a data de expiração, incluindo hoje e o último dia do período.')],
            'bo_section_cache'=>$section('Atualização do addon','O widget sempre coleta novamente. O cache abaixo se aplica somente às páginas do addon.'),
            'bo_ttl'=>['FriendlyName'=>'Cache do addon','Type'=>'dropdown','Options'=>['0'=>'Sem cache','30'=>'30 segundos','60'=>'60 segundos','120'=>'120 segundos','300'=>'5 minutos'],'Default'=>(string)$s['ttl'],'Description'=>$help('O botão Atualizar indicadores ignora o cache. Nenhuma rotina cron é necessária.')],
        ];
        $fields['bo_section_access']=$section('Controle de acesso','Selecione abaixo os perfis que podem acessar o addon e o widget. As permissões financeiras continuam sendo verificadas separadamente.');
        $fields['bo_section_widget']['Description'].=self::assets();
        return $fields;
    }
    private static function assets(): string
    {
        return <<<'HTML'
<style>
.avbo-native-help{display:block;margin-top:5px;color:#667785;font-size:12px;line-height:1.5}
tr.avbo-native-title>td{background:#e2ebf2!important;padding-top:12px!important;padding-bottom:12px!important}
tr.avbo-native-title>td:first-child{font-size:15px;font-weight:700;color:#002f57;vertical-align:top}
tr.avbo-native-title .avbo-native-help{font-size:13px;color:#536675;margin:0}
tr.avbo-native-title input[type=text]{display:none}
tr.avbo-native-field>td{padding-top:10px!important;padding-bottom:10px!important;vertical-align:middle}
</style>
<script>
(function(){function init(){document.querySelectorAll('.avbo-native-section').forEach(function(marker){var row=marker.closest('tr');if(row){row.classList.add('avbo-native-title');row.querySelectorAll(':scope > td, :scope > th').forEach(function(cell){cell.style.setProperty('background-color','#e2ebf2','important');cell.style.setProperty('padding-top','12px','important');cell.style.setProperty('padding-bottom','12px','important');});}});document.querySelectorAll('.avbo-native-help').forEach(function(help){var row=help.closest('tr');if(row&&!row.classList.contains('avbo-native-title'))row.classList.add('avbo-native-field');});}if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init);else init();})();
</script>
HTML;
    }
}
