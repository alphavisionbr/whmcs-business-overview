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
use WHMCS\Database\Capsule;
final class Admin
{
    private static function token(): string { return '<input type="hidden" name="token" value="'.View::e(generate_token('plain')).'">'; }
    public static function render(): void
    {
        if (!Access::allowed()) { echo '<div class="alert alert-danger">Acesso não autorizado.</div>'; return; }
        $page=in_array($_GET['page']??'',['settings','diagnostics'],true)?$_GET['page']:'overview';
        if ($page==='settings' && !Access::can('Configure Addon Modules')) { echo '<div class="alert alert-danger">Sem permissão para configurar módulos.</div>'; return; }
        $notice='';$fresh=false;
        if (($_SERVER['REQUEST_METHOD']??'GET')==='POST') {
            check_token('WHMCS.admin.default');
            if (($_POST['action']??'')==='save') {
                if (!Access::can('Configure Addon Modules')) { echo '<div class="alert alert-danger">Acesso não autorizado.</div>';return; }
                try { Settings::save($_POST);$notice='Configurações salvas.'; }
                catch (\InvalidArgumentException $e) { $notice=$e->getMessage(); }
                $page='settings';
            } elseif (($_POST['action']??'')==='refresh') { $fresh=true; }
        }
        echo View::css().'<div class="avbo avbo-admin"><header class="avbo-header"><div><p class="avbo-eyebrow">Alphavision WHMCS</p><h2>Business Overview <small>v1.0.5</small></h2><p>Indicadores para acompanhar o negócio e priorizar a operação.</p></div><div class="avbo-header-status"><span class="avbo-dot"></span>Módulo ativo</div></header><nav class="avbo-tabs">';
        $tabs=['overview'=>'Visão geral'];
        if (Access::can('Configure Addon Modules')) { $tabs['settings']='Configurações'; }
        $tabs['diagnostics']='Diagnóstico';
        foreach ($tabs as $p=>$label) { echo '<a'.($p===$page?' class="is-active"':'').' href="'.View::e(View::url($p)).'">'.View::e($label).'</a>'; }
        echo '</nav>';
        if ($notice!=='') { echo '<div class="alert alert-info" role="status">'.View::e($notice).'</div>'; }
        if ($page==='settings') { self::settings(); }
        else {
            echo '<form class="avbo-toolbar" method="post">'.self::token().'<input type="hidden" name="action" value="refresh"><button class="btn btn-primary" type="submit">Atualizar indicadores</button></form>';
            $data=Metrics::snapshot($fresh);
            if ($page==='diagnostics') { self::diagnostics($data); } else { echo View::dashboard($data); }
        }
        echo '<section class="avbo-card avbo-contribution"><div><p class="avbo-kicker">Projeto gratuito</p><h3>Apoie o desenvolvimento</h3><p>Este módulo é disponibilizado gratuitamente pela Alphavision®. Contribuições ajudam a manter testes de compatibilidade, correções e novas melhorias.</p></div><div class="avbo-contribution-actions"><button class="btn btn-primary" type="button" disabled title="O canal de contribuição será disponibilizado em breve.">Fazer uma contribuição</button><a class="btn btn-default" href="https://alphavision.com.br/whmcs" target="_blank" rel="noopener">Página do projeto</a><a class="btn btn-default" href="mailto:contato@alphavision.com.br?subject=Alphavision%20WHMCS%20Business%20Overview">Reportar um problema</a></div></section></div>';
    }
    private static function section(string $title, string $description): void
    { echo '<div class="avbo-section"><strong>'.View::e($title).'</strong><span>'.View::e($description).'</span></div>'; }
    private static function row(string $label, string $input, string $help): void
    { echo '<div class="avbo-setting"><div class="avbo-setting-label">'.View::e($label).'</div><div>'.$input.'<small class="avbo-help">'.View::e($help).'</small></div></div>'; }
    private static function check(string $name, bool $checked, string $label): string
    { return '<label><input type="checkbox" name="'.View::e($name).'" value="1"'.($checked?' checked':'').'> '.View::e($label).'</label>'; }
    private static function select(string $name, array $options, mixed $current): string
    {
        $out='<select class="form-control" aria-label="'.View::e($name).'" name="'.View::e($name).'">';
        foreach ($options as $v=>$l) { $out.='<option value="'.View::e($v).'"'.((string)$v===(string)$current?' selected':'').'>'.View::e($l).'</option>'; }
        return $out.'</select>';
    }
    private static function multiple(string $name, array $options, array $current): string
    {
        $out='<div class="avbo-options">';
        foreach ($options as $v=>$label) {
            $out.='<label><input type="checkbox" name="'.View::e($name).'[]" value="'.View::e($v).'"'.(in_array((string)$v,array_map('strval',$current),true)?' checked':'').'> '.View::e($label).'</label>';
        }
        return $out.(!$options?'<small>Nenhuma opção disponível.</small>':'').'</div>';
    }
    private static function settings(): void
    {
        $s=Settings::get();echo '<form method="post">'.self::token().'<input type="hidden" name="action" value="save">';
        self::section('Indicadores','Selecione os grupos e os números que devem aparecer.');
        self::row('Grupos',self::multiple('groups',Catalog::groups(),$s['groups']),'A permissão nativa de cada indicador também é obrigatória.');
        foreach (Catalog::groups() as $g=>$title) {
            $opts=[];foreach (Catalog::all() as $k=>$d) { if ($d['group']===$g) { $opts[$k]=$d['label']; } }
            self::row($title,self::multiple('indicators',$opts,$s['indicators']),'Desmarcar um indicador remove sua exibição e sua consulta individual. Consultas agregadas do mesmo grupo podem ser compartilhadas.');
        }
        self::section('Valores financeiros','Valores monetários ficam ocultos por padrão. Selecione explicitamente os perfis autorizados.');
        self::row('Exibir valores',self::check('money',$s['money'],'Habilitar valores monetários'),'As quantidades de faturas seguem a permissão List Invoices, mesmo com valores ocultos.');
        $roles=Capsule::table('tbladminroles')->orderBy('name')->pluck('name','id')->all();
        self::row('Perfis com acesso aos valores monetários',self::multiple('financial_roles',$roles,$s['financial_roles']),'Restrição adicional para dinheiro no widget e no addon. O Access Control nativo libera o módulo; esta seleção libera os valores, respeitando as permissões financeiras do WHMCS. Sem seleção, os valores ficam ocultos.');
        echo '<details class="avbo-classification"><summary>Classificação dos serviços (opcional)</summary><p>O módulo usa automaticamente o tipo do produto no WHMCS. Use estas exceções somente se um produto ou grupo precisar ser contado em outra categoria. As escolhas existentes são preservadas.</p>';
        self::section('Exceções de classificação','Produto selecionado tem prioridade sobre grupo e tipo nativo.');
        $groups=Capsule::table('tblproductgroups')->orderBy('name')->pluck('name','id')->all();
        $products=[];foreach (Capsule::table('tblproducts')->orderBy('name')->get(['id','name','gid']) as $p) { $products[$p->id]=$p->name.' (#'.$p->id.' · '.($groups[$p->gid]??'Sem grupo').')'; }
        foreach (['hosting'=>'Hospedagem','reseller'=>'Revenda'] as $k=>$label) {
            self::row($label.': grupos',self::multiple($k.'_groups',$groups,$s[$k.'_groups']),'Seleção opcional. Um grupo não pode ser selecionado nas duas categorias.');
            self::row($label.': produtos',self::multiple($k.'_products',$products,$s[$k.'_products']),'Produtos explícitos prevalecem sobre a classificação por grupo.');
        }
        echo '</details>';
        self::section('Suporte','Status ativos e aguardando resposta seguem a configuração nativa. Escolha os status dos outros dois indicadores.');
        $statuses=Capsule::table('tblticketstatuses')->orderBy('sortorder')->pluck('title','title')->all();
        self::row('Em progresso',self::multiple('progress',$statuses,$s['progress']),'Conta somente os departamentos atribuídos ao administrador.');
        self::row('Em análise',self::multiple('review',$statuses,$s['review']),'Os dois indicadores podem se sobrepor se o mesmo status for selecionado em ambos.');
        echo '<input type="hidden" name="complete" value="1"><div class="avbo-toolbar avbo-save"><button class="btn btn-primary" type="submit">Salvar configurações</button></div></form>';
    }
    private static function diagnostics(array $data): void
    {
        $rows=[
            ['Versão do módulo','1.0.5'],['PHP',PHP_VERSION],
            ['WHMCS',(string)\WHMCS\Config\Setting::getValue('Version')],
            ['Fuso horário',date_default_timezone_get().' (GMT'.date('P').')'],
            ['Departamentos atribuídos',implode(', ',Access::departments())?:'Nenhum. Os indicadores de suporte ficam zerados.'],
            ['Valores financeiros',Access::money($data['settings'])?'Perfil autorizado; permissões nativas aplicadas por indicador.':'Ocultos para este perfil.'],
            ['Última coleta',date('d/m/Y H:i:s',$data['at'])],
            ['Tempo de coleta',$data['ms'].' ms'],['Cache',(int)$data['settings']['ttl'].' segundos por sessão'],
            ['Automação','Sem cron e sem API externa. Atualização na abertura ou pelo botão.'],
            ['Estado dos indicadores',$data['errors']?'Há indicadores indisponíveis.':'Coleta concluída para os indicadores visíveis.'],
        ];
        $visible=count($data['values']);$failed=count(array_filter($data['values'],static fn($v)=>$v===null));
        echo '<div class="avbo-health"><div class="avbo-health-icon '.($failed?'is-warning':'is-ok').'">'.($failed?'!':'&#10003;').'</div><div><h3>'.($failed?'Coleta requer atenção':'Coleta dos indicadores concluída').'</h3><p>'.($visible-$failed).' disponíveis · '.$failed.' indisponíveis · '.(count(Catalog::all())-$visible).' ocultos por configuração ou acesso</p></div></div><section class="avbo-card"><p class="avbo-kicker">Diagnóstico</p><h3>Ambiente e coleta</h3><dl class="avbo-diagnostics">';
        foreach ($rows as [$k,$v]) { echo '<div><dt>'.View::e($k).'</dt><dd>'.View::e($v).'</dd></div>'; }
        echo '</dl></section><section class="avbo-card"><h3>Indicadores e permissões</h3><p>Uma falha nunca é apresentada como zero. Confira a compatibilidade da instalação se um indicador estiver indisponível.</p><div class="table-responsive"><table class="table"><thead><tr><th>Indicador</th><th>Permissão nativa</th><th>Situação</th></tr></thead><tbody>';
        foreach (Catalog::all() as $k=>$d) {
            $kind=array_key_exists($k,$data['values'])?($data['values'][$k]===null?'warning':'ok'):'muted';
            $state=array_key_exists($k,$data['values'])?($data['values'][$k]===null?'Indisponível':'Disponível'):'Oculto pela configuração ou permissão';
            echo '<tr><td>'.View::e($d['label']).'</td><td>'.View::e($d['permission']).'</td><td><span class="avbo-badge is-'.$kind.'">'.View::e($state).'</span></td></tr>';
        }
        echo '</tbody></table></div></section>';
    }
}
