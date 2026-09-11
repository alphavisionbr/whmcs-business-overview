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
final class Metrics
{
    private array $s;
    private array $memo=[];
    private string $today;
    private string $month;
    private string $tomorrow;
    private string $nextMonth;
    public function __construct(array $s)
    {
        $this->s=$s;
        $now=new \DateTimeImmutable('today');
        $this->today=$now->format('Y-m-d');
        $this->month=$now->format('Y-m-01');
        $this->tomorrow=$now->modify('+1 day')->format('Y-m-d');
        $this->nextMonth=$now->modify('first day of next month')->format('Y-m-d');
    }
    public static function snapshot(bool $fresh=false): array
    {
        if (!Access::allowed()) { return []; }
        $s=Settings::get(); $defs=Catalog::all(); $visible=[];
        foreach ($defs as $k=>$d) {
            if (in_array($d['group'],$s['groups'],true) && in_array($k,$s['indicators'],true) && Access::metric($d,$s)) { $visible[$k]=$d; }
        }
        // The entire authorization context is re-evaluated BEFORE any cached data is read.
        $key=hash('sha256',json_encode([AVBO_MODULE,'1.0.5',Access::admin()->id,Access::admin()->roleId,Access::departments(),array_keys($visible),$s,date('Y-m-d'),date_default_timezone_get()]));
        $cache=$_SESSION['avbo_cache']??[];
        if (!$fresh && $s['ttl']>0 && ($cache['key']??'')===$key && ($cache['expires']??0)>time()) { return $cache['data']; }
        $start=microtime(true);$engine=new self($s);$values=[];$errors=[];
        foreach ($visible as $k=>$d) {
            try { $values[$k]=$engine->value($k); }
            catch (\Throwable $e) { $values[$k]=null;$errors[]=$k; }
        }
        $data=['values'=>$values,'errors'=>$errors,'at'=>time(),'ms'=>(int)round((microtime(true)-$start)*1000),'settings'=>$s];
        $_SESSION['avbo_cache']=['key'=>$key,'expires'=>time()+$s['ttl'],'data'=>$data];
        return $data;
    }
    private function period($q, string $column, bool $month=false)
    {
        return $q->where($column,'>=',$month?$this->month:$this->today)->where($column,'<',$month?$this->nextMonth:$this->tomorrow);
    }
    private function services(): array
    {
        if (isset($this->memo['services'])) { return $this->memo['services']; }
        $out=['services_active'=>0,'hosting'=>0,'reseller'=>0,'other'=>0,'services_suspended'=>0,'services_pending'=>0];
        $rows=Capsule::table('tblhosting as h')->leftJoin('tblproducts as p','p.id','=','h.packageid')
            ->whereIn('h.domainstatus',['Active','Suspended','Pending'])
            ->select('h.domainstatus','h.packageid','p.gid','p.type')->selectRaw('COUNT(*) AS quantity')
            ->groupBy('h.domainstatus','h.packageid','p.gid','p.type')->get();
        foreach ($rows as $r) {
            $n=(int)$r->quantity;
            if ($r->domainstatus==='Active') {
                $out['services_active']+=$n;
                $out[Settings::classify((int)$r->packageid,(int)$r->gid,(string)$r->type,$this->s)]+=$n;
            } else { $out[$r->domainstatus==='Pending'?'services_pending':'services_suspended']+=$n; }
        }
        $out['hosting_reseller']=$out['hosting']+$out['reseller'];
        return $this->memo['services']=$out;
    }
    private function tickets(): array
    {
        if (isset($this->memo['tickets'])) { return $this->memo['tickets']; }
        $out=array_fill_keys(['tickets_active','tickets_waiting','tickets_flagged','tickets_progress','tickets_review'],0);
        $deps=Access::departments();
        if (!$deps) { return $this->memo['tickets']=$out; }
        $statuses=Capsule::table('tblticketstatuses')->get()->keyBy('title');
        $wanted=array_merge($this->s['progress'],$this->s['review']);
        foreach ($statuses as $st) { if ($st->showactive || $st->showawaiting) { $wanted[]=$st->title; } }
        $rows=Capsule::table('tbltickets')->whereIn('did',$deps)->whereIn('status',array_values(array_unique($wanted)))->select('status','flag')->selectRaw('COUNT(*) AS quantity')->groupBy('status','flag')->get();
        foreach ($rows as $r) {
            $st=$statuses->get($r->status);$n=(int)$r->quantity;
            if ($st && $st->showactive) {
                $out['tickets_active']+=$n;
                if ((int)$r->flag===(int)Access::admin()->id) { $out['tickets_flagged']+=$n; }
            }
            if ($st && $st->showawaiting) { $out['tickets_waiting']+=$n; }
            if (in_array($r->status,$this->s['progress'],true)) { $out['tickets_progress']+=$n; }
            if (in_array($r->status,$this->s['review'],true)) { $out['tickets_review']+=$n; }
        }
        return $this->memo['tickets']=$out;
    }
    private function balances(): array
    {
        if (isset($this->memo['balances'])) { return $this->memo['balances']; }
        $paid=Capsule::table('tblaccounts as a')->join('tblinvoices as open_invoice','open_invoice.id','=','a.invoiceid')->where('open_invoice.status','Unpaid')->select('a.invoiceid')->selectRaw('SUM(a.amountin - a.amountout) AS paid')->groupBy('a.invoiceid');
        $balance='GREATEST(0, i.total - i.credit - COALESCE(p.paid, 0))';
        $rows=Capsule::table('tblinvoices as i')->join('tblclients as c','c.id','=','i.userid')
            ->leftJoinSub($paid,'p',static function($join){$join->on('p.invoiceid','=','i.id');})
            ->leftJoin('tblcurrencies as cur','cur.id','=','c.currency')->where('i.status','Unpaid')
            ->select('c.currency','cur.code')->selectRaw('SUM('.$balance.') AS balance_open')
            ->selectRaw('SUM(CASE WHEN i.duedate < ? THEN '.$balance.' ELSE 0 END) AS balance_overdue',[$this->today])
            ->groupBy('c.currency','cur.code')->orderBy('c.currency')->get();
        $out=['balance_open'=>[],'balance_overdue'=>[]];
        foreach ($rows as $r) {
            $code=$r->code?:'Moeda #'.(int)$r->currency;
            foreach (array_keys($out) as $k) { $out[$k][$code]=(string)$r->$k; }
        }
        return $this->memo['balances']=$out;
    }
    private function income(string $key): array
    {
        if (!isset($this->memo['income'])) {
            $r=localAPI('GetStats',[],Access::admin()->username);
            if (($r['result']??'')!=='success') { throw new \RuntimeException('GetStats indisponível'); }
            $this->memo['income']=$r;
        }
        $field=$key==='income_today'?'income_today':'income_thismonth';
        $n=$this->memo['income'][$field]??null;
        if (!is_numeric($n)) { throw new \RuntimeException('Receita inválida'); }
        $currency=Capsule::table('tblcurrencies')->where('default',1)->value('code');
        if (!$currency) { throw new \RuntimeException('Moeda padrão não identificada'); }
        return [(string)$currency=>(string)$n];
    }
    public function value(string $key): mixed
    {
        if (in_array($key,['services_active','hosting_reseller','hosting','reseller','other','services_suspended','services_pending'],true)) { return $this->services()[$key]; }
        if (str_starts_with($key,'tickets_')) { return $this->tickets()[$key]; }
        if (str_starts_with($key,'balance_')) { return $this->balances()[$key]; }
        if (str_starts_with($key,'income_')) { return $this->income($key); }
        return match ($key) {
            'orders_today'=>$this->period(Capsule::table('tblorders'),'date')->count(),
            'orders_pending'=>Capsule::table('tblorders')->where('status','Pending')->count(),
            'orders_complete'=>$this->period(Capsule::table('tblorders')->where('status','Active'),'date',true)->count(),
            'quotes'=>Capsule::table('tblquotes')->whereIn('stage',['Draft','Delivered'])->where('validuntil','>=',$this->today)->count(),
            'clients_new'=>$this->period(Capsule::table('tblclients'),'datecreated',true)->count(),
            'clients_active'=>Capsule::table('tblclients')->where('status','Active')->count(),
            'invoices_open'=>Capsule::table('tblinvoices')->where('status','Unpaid')->count(),
            'invoices_overdue'=>Capsule::table('tblinvoices')->where('status','Unpaid')->where('duedate','<',$this->today)->count(),
            'cancellations'=>Capsule::table('tblcancelrequests as r')->join('tblhosting as h','h.id','=','r.relid')->whereNotIn('h.domainstatus',['Cancelled','Terminated'])->distinct()->count('r.relid'),
            'domains_active'=>Capsule::table('tbldomains')->where('status','Active')->count(),
            'domains_expiring'=>Capsule::table('tbldomains')->where('status','Active')->where('expirydate','>=',$this->today)->where('expirydate','<=',(new \DateTimeImmutable($this->today))->modify('+'.$this->s['days'].' days')->format('Y-m-d'))->count(),
            default=>throw new \InvalidArgumentException('Indicador desconhecido'),
        };
    }
}
