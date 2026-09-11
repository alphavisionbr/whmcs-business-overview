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
final class Settings
{
    public const TABLE = 'mod_alphavision_business_overview';
    public static function ids(string $value): array
    {
        $out=[];
        foreach (explode(',', $value) as $id) {
            $id=trim($id);
            if (ctype_digit($id) && (int)$id>0) { $out[]=(int)$id; }
        }
        return array_values(array_unique($out));
    }
    public static function defaults(): array
    {
        return ['widget'=>true,'money'=>false,'financial_roles'=>[], 'hide_zero'=>false,
            'layout'=>'compact','attention'=>true,'days'=>30,'ttl'=>60,
            'groups'=>array_keys(Catalog::groups()), 'indicators'=>array_keys(Catalog::all()),
            'hosting_groups'=>[], 'reseller_groups'=>[], 'hosting_products'=>[], 'reseller_products'=>[],
            'progress'=>['In Progress'],'review'=>['On Hold']];
    }
    public static function get(): array
    {
        $json=Capsule::table(self::TABLE)->where('id',1)->value('settings');
        $stored=json_decode((string)$json,true);
        $s=array_replace(self::defaults(), is_array($stored)?$stored:[]);
        if (array_intersect(['hosting','reseller'],$s['indicators'])) { $s['indicators'][]='hosting_reseller'; }
        $s['indicators']=array_values(array_unique(array_diff($s['indicators'],['hosting','reseller','services_active'])));
        $native=Capsule::table('tbladdonmodules')->where('module',AVBO_MODULE)->pluck('value','setting')->all();
        foreach (['widget','hide_zero','attention'] as $key) {
            if (array_key_exists('bo_'.$key,$native)) { $s[$key]=in_array($native['bo_'.$key],['on','1',1,true],true); }
        }
        if (isset($native['bo_layout']) && in_array($native['bo_layout'],['compact','full'],true)) { $s['layout']=$native['bo_layout']; }
        foreach (['days'=>[7,15,30],'ttl'=>[0,30,60,120,300]] as $key=>$allowed) {
            if (isset($native['bo_'.$key]) && in_array((int)$native['bo_'.$key],$allowed,true)) { $s[$key]=(int)$native['bo_'.$key]; }
        }
        return $s;
    }
    public static function save(array $post): void
    {
        if (!isset($post['complete'])) { throw new \InvalidArgumentException('Formulário incompleto. Nenhuma configuração foi alterada.'); }
        $s=self::get();
        $s['money']=isset($post['money']);
        foreach (['groups'=>array_keys(Catalog::groups()),'indicators'=>array_keys(Catalog::all())] as $key=>$allowed) {
            $s[$key]=array_values(array_intersect($allowed,is_array($post[$key]??null)?$post[$key]:[]));
        }
        foreach (['financial_roles'=>'tbladminroles','hosting_groups'=>'tblproductgroups','reseller_groups'=>'tblproductgroups','hosting_products'=>'tblproducts','reseller_products'=>'tblproducts'] as $key=>$table) {
            $values=is_array($post[$key]??null)?$post[$key]:[];
            $values=array_map('intval',array_filter($values,'is_scalar'));
            $s[$key]=array_map('intval',Capsule::table($table)->whereIn('id',$values)->pluck('id')->all());
        }
        foreach (['progress','review'] as $key) {
            $s[$key]=Capsule::table('tblticketstatuses')->whereIn('title',is_array($post[$key]??null)?$post[$key]:[])->pluck('title')->all();
        }
        if (array_intersect($s['hosting_products'],$s['reseller_products']) || array_intersect($s['hosting_groups'],$s['reseller_groups'])) {
            throw new \InvalidArgumentException('Um produto ou grupo não pode pertencer simultaneamente a Hospedagem e Revenda.');
        }
        Capsule::table(self::TABLE)->where('id',1)->update(['settings'=>json_encode($s,JSON_UNESCAPED_UNICODE)]);
        unset($_SESSION['avbo_cache']);
        if (function_exists('logActivity')) { logActivity('Alphavision Business Overview: configurações atualizadas.'); }
    }
    public static function classify(int $product, int $group, string $type, array $s): string
    {
        if (in_array($product,$s['hosting_products'],true)) { return 'hosting'; }
        if (in_array($product,$s['reseller_products'],true)) { return 'reseller'; }
        if (in_array($group,$s['hosting_groups'],true)) { return 'hosting'; }
        if (in_array($group,$s['reseller_groups'],true)) { return 'reseller'; }
        return ['hostingaccount'=>'hosting','reselleraccount'=>'reseller'][$type]??'other';
    }
}
