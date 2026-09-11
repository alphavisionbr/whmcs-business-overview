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
if (!defined('WHMCS')) { exit; }
require_once __DIR__.'/bootstrap.php';
add_hook('AdminHomeWidgets', 1, static function () {
    try {
        if (\Alphavision\BusinessOverview\Access::allowed() && \Alphavision\BusinessOverview\Settings::get()['widget']) {
            return new \AlphavisionBusinessOverviewWidget();
        }
    } catch (\Throwable $e) { return null; }
    return null;
});

add_hook('AdminAreaFooterOutput',50,static function($vars){
    if(!\Alphavision\BusinessOverview\Access::allowed())return '';
    // Links relativos como # herdam a query da página atual: examine primeiro o href literal.
    return <<<'HTML'
<script>
(function(){
 document.querySelectorAll('nav a[href],header a[href],#sidebar a[href],#menu a[href],.navigation a[href]').forEach(function(a){
  if(a.closest('.avbo-admin'))return;
  var raw=(a.getAttribute('href')||'').trim();
  var literalPath=raw.split(/[?#]/)[0];
  if(!(literalPath==='addonmodules.php'||literalPath.endsWith('/addonmodules.php')))return;
  try{
   var u=new URL(raw,location.href),pairs=Array.from(u.searchParams.entries());
   if(u.origin!==location.origin||u.hash||pairs.length!==1||pairs[0][0]!=='module'||pairs[0][1]!=='alphavision_whmcs_business_overview')return;
   a.textContent='Business Overview';
  }catch(e){}
 });
})();
</script>
HTML;
});
