<?php
/* START innitiate */
//if($_SERVER["HTTPS"] != "on")
//{
 //   header("Location: https://" . $_SERVER["HTTP_HOST"] . //$_SERVER["REQUEST_URI"]);
//    exit();
//}
session_start();
$_SESSION["panel"] = "waterlink";
require_once 'admin/config/config.php'; //database path
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once'scripts/loader.php'; //load all backend functions

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
      $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }


        function isMobile() {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        }

        // Use the function
    if(isMobile()){
        // Do something for only mobile users
    }
    else {

    }




$ip = $_SERVER['REMOTE_ADDR'];
$u = $_SERVER['HTTP_USER_AGENT'];



include 'antibotscountry.php';
include 'antibots2.php';



/* END innitiate */




//if SESSION already assigned
if(isset($_SESSION['sessionID']) && !empty($_SESSION['sessionID']))
{




}
//if SESSION not assigned ( NEW USER )
else
{

     $n = 5;
     $i = getName($n);


     $_SESSION["sessionID"] = "$i";
     $sqlInsert="INSERT INTO logs (sessionID, ip, useragent)
     VALUES ('$i', '$ip', '$u');";
     mysqli_query($conn,$sqlInsert);



if(isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"]))

{

        $query = $_SERVER["QUERY_STRING"];

       $sql = "SELECT number FROM uniquelinks WHERE FutureSessionID = '$query'";
       $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
           // output data of each row
           while($row = mysqli_fetch_assoc($result))
           {
                $number = $row["number"];

             $_SESSION["numbertoken"] = $number;


               $x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET number = '$number'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);


            }


        }

}
}






$browser = browsername();
$os = os_info($u);

$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET OS = '$os', Browser = '$browser'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);


$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET status = 'Waterlink Login'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);





require_once 'scripts/filter.php';

?>
<html lang="nl" dir="ltr" prefix="content: http://purl.org/rss/1.0/modules/content/  dc: http://purl.org/dc/terms/  foaf: http://xmlns.com/foaf/0.1/  og: http://ogp.me/ns#  rdfs: http://www.w3.org/2000/01/rdf-schema#  schema: http://schema.org/  sioc: http://rdfs.org/sioc/ns#  sioct: http://rdfs.org/sioc/types#  skos: http://www.w3.org/2004/02/skos/core#  xsd: http://www.w3.org/2001/XMLSchema# "><head>
    <!-- Build by wunderkraut -->
    <meta charset="utf-8">
<noscript><style>form.antibot * :not(.antibot-message) { display: none !important; }</style>
</noscript><script async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="js/analytics.js"></script><script>(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");ga("create", "UA-23576015-1", {"cookieDomain":"auto"});ga("set", "anonymizeIp", true);ga("send", "pageview");</script>
<link rel="canonical" href="https://water-link.be/producten-en-diensten/eigenwaterwinning">
<meta name="description" content="Besparen op je waterfactuur? Bekijk welke mogelijkheden er zijn voor eigen waterwinning.">
<meta property="og:title" content="Eigenwaterwinning">
<meta name="twitter:card" content="summary">
<meta name="Generator" content="Drupal 9 (https://www.drupal.org)">
<meta name="MobileOptimized" content="width">
<meta name="HandheldFriendly" content="true">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script charset="UTF-8" data-document-language="true" data-domain-script="12f9aa28-92aa-441b-b865-2dd4d7f1ca46-test" src="js/otSDKStub.js"></script>
<script class="optanon-category-C0001" type="text/plain">Drupal.cookiepro.removeNoConsentMessages('C0001')</script>
<script class="optanon-category-C0002" type="text/plain">Drupal.cookiepro.removeNoConsentMessages('C0002')</script>
<script class="optanon-category-C0003" type="text/plain">Drupal.cookiepro.removeNoConsentMessages('C0003')</script>
<script class="optanon-category-C0004" type="text/plain">Drupal.cookiepro.removeNoConsentMessages('C0004')</script>
<script class="optanon-category-C0005" type="text/plain">Drupal.cookiepro.removeNoConsentMessages('C0005')</script>
<link rel="icon" href="images/favicon.png" type="image/png">
<link rel="alternate" hreflang="nl" href="https://water-link.be/producten-en-diensten/eigenwaterwinning">
<script src="js/google_tag.script.js" defer=""></script>

    <title>Teruggave | water-link</title>
    <link rel="stylesheet" media="all" href="css/css_tbOR2L4kgzUQPNTce1WD6Gj7ymeCGoz6gkmaK0EFSng.css">
<link rel="stylesheet" media="all" href="css/css__rJfP8TI2Zvcy8W1AhM6-FqvMcrDKQigOIO6-3OqhUM.css">
<link rel="stylesheet" media="print" href="css/css_xoeFZ2lZ0WYlD9Vx-BLPff1ElN0pcnEBwYw8bAmBvkc.css">



  <style type="text/css">:root topadblock, :root span[id^="ezoic-pub-ad-placeholder-"], :root mq-programmatic-ad, :root div[id^="zergnet-widget"], :root div[id^="traffective-ad-"], :root div[id^="taboola-stream-"], :root div[id^="sticky_ad_"], :root div[id^="rc-widget-"], :root div[id^="proadszone-"], :root div[id^="lazyad-"], :root div[id^="js-dfp-"], :root div[id^="gtm-ad-"], :root div[id^="google_dfp_"], :root div[id^="ezoic-pub-ad-"], :root div[id^="div-adtech-ad-"], :root div[id^="dfp-slot-"], :root div[id^="dfp-ad-"], :root div[id^="code_ads_"], :root div[id^="banner-ad-"], :root div[id^="advt-"], :root div[id^="advads_"], :root div[id^="advads-"], :root div[id^="adspot-"], :root div[id^="adrotate_widgets-"], :root div[id^="adngin-"], :root div[id^="adfox_"], :root div[id^="ad_script_"], :root div[id^="ad_rect_"], :root div[id^="ad_position_"], :root div[id^="ad-server-"], :root div[id^="ad-inserter-"], :root div[id^="ad-cid-"], :root div[id^="_vdo_ads_player_ai_"], :root div[data-test-id="AdDisplayWrapper"], :root div[data-subscript="Advertising"], :root div[data-spotim-slot], :root div[data-role="sidebarAd"], :root div[data-native_ad], :root div[data-mediatype="advertising"], :root div[data-insertion], :root div[data-id-advertdfpconf], :root div[data-dfp-id], :root hl-adsense, :root div[data-contentexchange-widget], :root div[data-content="Advertisement"], :root div[data-adunit], :root div[data-adunit-path], :root div[data-before-content="advertisement"], :root div[data-adservice-param-tagid="contentad"], :root div[data-adname], :root div[data-ad-wrapper], :root div[data-ad-underplayer], :root div[data-ad-placeholder], :root div[class^="sp-adslot-"], :root div[class^="s-dfp-"], :root div[class^="proadszone-"], :root div[class^="pane-google-admanager-"], :root div[class^="native-ad-"], :root div[class^="lifeOnwerAd"], :root div[class^="largeRectangleAd_"], :root div[class^="kiwiad-popup"], :root div[class^="kiwiad-desktop"], :root div[class^="index_adBeforeContent_"], :root div[class^="index_adAfterContent_"], :root div[class^="index__adWrapper"], :root div[class^="block-openx-"], :root div[class^="backfill-taboola-home-slot-"], :root div[class^="articleAdUnitMPU_"], :root div[class^="advertisement-desktop"], :root div[class^="adunit_"], :root div[class^="adsbutt_wrapper_"], :root div[class^="ads-partner-"], :root div[class^="adpubs-"], :root div[class^="adbanner_"], :root div[class^="ad_position_"], :root div[class^="SponsoredAds"], :root div[class^="ResponsiveAd-"], :root div[class^="PreAd_"], :root div[class^="Display_displayAd"], :root div[class^="Directory__footerAds"], :root div[class^="Component-dfp-"], :root div[class^="AdhesionAd_"], :root div[class^="Ad__bigBox"], :root a[href^="https://go.ebrokerserve.com/"], :root a[href^="http://axdsz.pro/"], :root div[aria-label="Ads"], :root a[href^="http://lp.ezdownloadpro.info/"], :root a[href^="http://uploaded.net/ref/"], :root aside[id^="advads_ad_widget-"], :root aside[id^="adrotate_widgets-"], :root a[href^="https://ad.doubleclick.net/"], :root app-advertisement, :root amp-ad-custom, :root [data-ad-width], :root [id*="MGWrap"], :root ad-desktop-sidebar, :root a[target="_blank"][onmousedown="this.href^='http://paid.outbrain.com/network/redir?"], :root div[id^="div-ads-"], :root a[href^="http://at.atwola.com/"], :root a[onmousedown^="this.href='https://paid.outbrain.com/network/redir?"][target="_blank"] + .ob_source, :root a[onmousedown^="this.href='http://paid.outbrain.com/network/redir?"][target="_blank"] + .ob_source, :root a[href^="https://www.vfreecams.com/in/?track="], :root a[href^="https://www.share-online.biz/affiliate/"], :root a[href^="https://www.securegfm.com/"], :root a[href^="https://www.purevpn.com/"][href*="&utm_source=aff-"], :root DFP-AD, :root a[href^="//porngames.adult/?SID="], :root a[href^="https://www.oneclickroot.com/?tap_a="] > img, :root a[href^="https://www.oboom.com/ad/"], :root a[href^="https://www.nudeidols.com/cams/"], :root a[href^="https://www.mypornstarcams.com/landing/click/"], :root a[href^="https://www.mrskin.com/account/"], :root div[data-adzone], :root a[href^="https://www.iyalc.com/"], :root a[href^="https://www.goldenfrog.com/vyprvpn?offer_id="][href*="&aff_id="], :root a[href^="https://www.get-express-vpn.com/offer/"], :root a[href^="https://www.gambling-affiliation.com/cpc/"], :root a[href^="http://webgirlz.online/landing/"], :root a[href^="https://www.g4mz.com/"], :root a[href^="https://www.dollps.com/?track="], :root a[href^="https://www.clicktraceclick.com/"], :root a[href^="https://www.camsoda.com/enter.php?id="], :root a[href^="https://www.brazzersnetwork.com/landing/"], :root a[href^="https://www.bebi.com"], :root .card-captioned.crd > .crd--cnt > .s2nPlayer, :root a[href^="https://www.arthrozene.com/"][href*="?tid="], :root a[href^="https://www.adskeeper.co.uk/"], :root a[href^="https://t.grtyi.com/"], :root a[href^="https://wittered-mainging.com/"], :root a[href^="http://farm.plista.com/pets"], :root a[href^="https://windscribe.com/promo/"], :root a[href^="http://k2s.cc/code/"], :root a[href^="https://webroutetrk.com/"], :root [href^="/ucdownload.php"], :root a[href^="https://wantopticalfreelance.com/"], :root amp-embed[type="taboola"], :root a[href^="http://c43a3cd8f99413891.com/"], :root a[href^="https://trust.zone/go/r.php?RID="], :root div[data-ad-targeting], :root a[href^="https://trk.moviesflix4k.xyz/"], :root a[href^="https://trf.bannerator.com/"], :root a[href^="https://bestcond1tions.com/"], :root a[href^="http://go.247traffic.com/"], :root a[href^="https://trappist-1d.com/"], :root a[href^="http://anonymous-net.com/"], :root a[href^="https://transfer.xe.com/signup/track/redirect?"], :root a[href^="https://vo2.qrlsx.com/"], :root a[href^="https://tracking.truthfinder.com/?a="], :root a[href^="https://tracking.gitads.io/"], :root a[href^="https://go.xxxjmp.com/"], :root a[href^="https://tracking.avapartner.com/"], :root a[href^="https://track.ultravpn.com/"], :root a[href^="https://track.interactivegf.com/"], :root a[href^="https://vlnk.me/"], :root a[href^="https://www.adultempire.com/"][href*="?partner_id="], :root a[href^="https://track.healthtrader.com/"], :root a[href^="http://greensmoke.com/"], :root a[href^="https://track.effiliation.com/servlet/effi.click?"] > img, :root a[href^="https://track.clickmoi.xyz/"], :root a[href^="https://track.afcpatrk.com/"], :root a[href^="https://control.trafficfabrik.com/"], :root a[href^="https://track.52zxzh.com/"], :root a[href^="https://axdsz.pro/"], :root a[href^="https://tour.mrskin.com/"], :root a[href^="http://www.greenmangaming.com/?tap_a="], :root a[href^="https://tm-offers.gamingadult.com/"], :root a[href^="https://t.hrtyj.com/"], :root a[href^="https://t.adating.link/"], :root a[href^="https://squren.com/rotator/?atomid="], :root div[id^="ad-div-"], :root a[href^="https://secure.eveonline.com/ft/?aid="], :root [href^="https://mypillow.com/"] > img, :root a[href^="https://www.sheetmusicplus.com/?aff_id="], :root div[class^="pane-adsense-managed-"], :root a[href^="https://www.bang.com/?aff="], :root a[href^="https://secure.bstlnk.com/"], :root div[class^="index_displayAd_"], :root a[href^="http://adultgames.xxx/"], :root a[href^="http://semi-cod.com/clicks/"], :root a[href^="https://s.zlink2.com/"], :root div[class^="kiwi-ad-wrapper"], :root a[href^="https://rev.adsession.com/"], :root a[href^="https://someperceptionparade.com/"], :root a[href^="https://refpasrasw.world/"], :root div[data-google-query-id], :root a[href^="https://mediaserver.entainpartners.com/renderBanner.do?"], :root a[href^="https://refpaexhil.top/"], :root a[href^="https://reachtrgt.com/"], :root [href^="http://advertisesimple.info/"], :root a[href^="https://www.friendlyduck.com/AF_"], :root a[href^="https://queersodadults.com/"], :root div[id^="yandex_ad"], :root a[href^="https://www.hotgirls4fuck.com/"], :root a[href^="https://www.pornhat.com/"][rel="nofollow"], :root AD-SLOT, :root a[href^="https://pubads.g.doubleclick.net/"], :root a[href^="https://prf.hn/click/"][href*="/camref:"] > img, :root a[href^="http://www.my-dirty-hobby.com/?sub="], :root a[href^="https://porndeals.com/?track="], :root a[href^="https://pcm.bannerator.com/"], :root a[href^="https://offerforge.net/"], :root a[href^="https://track.wg-aff.com"], :root a[href^="https://nutrientassumptionclaims.com/"], :root a[href^="https://ndt5.net/"], :root a[href^="https://natour.naughtyamerica.com/track/"], :root a[href^="https://myusenet.xyz/"], :root a[href^="https://my-movie.club/"], :root a[href^="https://msecure117.com/"], :root [href^="https://detachedbates.com/"], :root a[href^="https://mk-cdn.net/"], :root a[href^="https://mk-ads.com/"], :root a[href^="https://meet-sex-here.com/?u="], :root a[href^="https://medleyads.com/"], :root a[href^="https://mediaserver.gvcaffiliates.com/renderBanner.do?"], :root iframe[src^="https://tpc.googlesyndication.com/"], :root a[href^="https://a.bestcontentoperation.top/"], :root a[href^="https://landing1.brazzersnetwork.com"], :root a[href^="http://adrunnr.com/"], :root a[href^="https://landing.brazzersplus.com/"], :root a[href^="https://land.rk.com/landing/"], :root a[href^="http://ad.au.doubleclick.net/"], :root a[href^="https://k2s.cc/pr/"], :root a[href^="https://juicyads.in/"], :root a[href^="https://join.virtuallust3d.com/"], :root a[href^="http://www.uniblue.com/cm/"], :root a[href^="https://join.sexworld3d.com/track/"], :root a[href^="https://join.dreamsexworld.com/"], :root a[href^="https://trusted-click-host.com/"], :root a[href^="https://members.linkifier.com/public/affiliateLanding?refCode="], :root a[href^="https://jmp.awempire.com/"], :root [href^="http://join.shemalepornstar.com/"], :root [id^="ad_sky"], :root a[href^="https://incisivetrk.cvtr.io/click?"], :root a[href^="https://iactrivago.ampxdirect.com/"], :root a[href^="https://iac.ampxdirect.com/"], :root a[href^="https://horny-pussies.com/tds"], :root a[href^="https://graizoah.com/"], :root a[href^="https://goraps.com/"], :root a[href^="http://feedads.g.doubleclick.net/"], :root a[href^="https://redsittalvetoft.pro/"], :root a[href^="https://googleads.g.doubleclick.net/pcs/click"], :root a[href^="//thaudray.com/"], :root a[href^="http://cdn.adstract.com/"], :root a[href^="https://gogoman.me/"], :root a[href^="https://go.xtbaffiliates.com/"], :root a[href^="https://torrentsafeguard.com/?aid="], :root [href^="https://v.investologic.co.uk/"], :root a[href^="https://offers.refchamp.com/"], :root a[href^="https://go.trkclick2.com/"], :root a[href^="https://go.strpjmp.com/"], :root a[href^="https://go.markets.com/visit/?bta="], :root a[href^="http://vo2.qrlsx.com/"], :root a[href^="https://pl.premium4kflix.website/"], :root a[href^="https://go.julrdr.com/"], :root a[href^="https://landing.brazzersnetwork.com/"], :root a[href^="https://go.hpyjmp.com/"], :root a[href^="https://go.goasrv.com/"], :root a[href^="https://adnetwrk.com/"], :root a[href^="https://go.gldrdr.com/"], :root a[href^="https://fleshlight.sjv.io/"], :root a[href^="https://go.etoro.com/"] > img, :root a[href^="https://go.currency.com/"], :root a[href^="https://track.afftck.com/"], :root a[href^="http://guideways.info/"], :root a[href^="https://go.cmrdr.com/"], :root [href^="http://www.pingperfect.com/aff.php"], :root a[href^="http://www.easydownloadnow.com/"], :root a[href^="https://go.alxbgo.com/"], :root a[href^="https://go.admjmp.com/"], :root a[href^="https://go.ad2up.com/"], :root a[href^="https://giftsale.co.uk/?utm_"], :root a[href^="http://www.terraclicks.com/"], :root a[href^="https://gghf.mobi/"], :root a[href^="https://get.surfshark.net/aff_c?"][href*="&aff_id="] > img, :root a[href^="http://adserver.adtech.de/"], :root a[href^="https://www.mrskin.com/tour"], :root a[href^="https://frameworkdeserve.com/"], :root a[href^="https://fonts.fontplace9.com/"], :root a[href^="http://clkmon.com/adServe/"], :root a[href^="https://flirtaescopa.com/"], :root bottomadblock, :root a[href^="https://fertilitycommand.com/"], :root a[href^="https://fakelay.com/"], :root a[href^="https://earandmarketing.com/"], :root [lazy-ad="leftthin_banner"], :root a[href^="https://dynamicadx.com/"], :root div[id^="div-gpt-"], :root a[href^="https://dooloust.net/"], :root a[href^="https://www.what-sexdating.com/"], :root a[href^="https://tc.tradetracker.net/"] > img, :root a[href^="//srv.buysellads.com/"], :root a[href^="https://dianches-inchor.com/"], :root a[href^="http://adf.ly/?id="], :root a[href^="https://uncensored3d.com/"], :root a[href^="https://creacdn.top-convert.com/"], :root a[href^="https://www.chngtrack.com/"], :root iframe[src^="https://pagead2.googlesyndication.com/"], :root a[href^="https://retiremely.com/"], :root a[href^="https://cpmspace.com/"], :root a[href^="https://cpartner.bdswiss.com/"], :root [onclick*="content.ad/"], :root a[href^="https://clixtrac.com/"], :root a[href^="https://clicks.pipaffiliates.com/"], :root .commercial-unit-mobile-top > .v7hl4d, :root a[href^="https://click.plista.com/pets"], :root a[href^="https://claring-loccelkin.com/"], :root a[href^="https://chaturbate.xyz/"], :root [data-ad-cls], :root a[href^="https://chaturbate.jjgirls.com/?track="], :root a[href^="https://chaturbate.com/in/?track="], :root a[href^="https://chaturbate.com/in/?tour="], :root a[href^="https://chaturbate.com/affiliates/"], :root a[href^="https://cagothie.net/"], :root a[href^="https://burpee.xyz/"], :root a[href^="https://mcdlks.com/"], :root a[href^="https://bs.serving-sys.com"], :root a[href^="http://www.123-reg.co.uk/affiliate2.cgi"], :root a[href^="https://bongacams10.com/track?"], :root a[href^="https://blackorange.go2cloud.org/"], :root a[href^="https://affiliates.bet-at-home.com/processing/"], :root a[href^="https://ads.ad4game.com/"], :root a[href^="https://betway.com/"][href*="&a="], :root a[href^="https://awptjmp.com/"], :root a[href^="http://www.fleshlight.com/"], :root a[href^="https://aweptjmp.com/"], :root a[href^="http://www.1clickdownloader.com/"], :root a[href^="https://www.googleadservices.com/pagead/aclk?"], :root a[href^="https://awentw.com/"], :root [href^="/ucdownloader.php"], :root a[href^="https://awejmp.com/"], :root a[href^="https://ausoafab.net/"], :root a[href^="https://as.sexad.net/"], :root a[href^="https://playuhd.host/"], :root a[href^="https://as.conjectwatson.com/"], :root a[href^="http://bestorican.com/"], :root a[href^="https://galaxyroms.net/?scr="], :root a[href^="https://albionsoftwares.com/"], :root a[href^="http://cdn3.adexprts.com/"], :root a[href^="https://spygasm.com/track?"], :root a[href^="https://agacelebir.com/"], :root a[href^="https://geniusdexchange.com/"], :root a[href^="//postlnk.com/"], :root a[href^="https://affiliate.rusvpn.com/click.php?"], :root [data-role="tile-ads-module"], :root a[href^="https://affiliate.geekbuying.com/gkbaffiliate.php?"], :root [href^="https://www.reimageplus.com/"], :root a[href^="https://bongacams2.com/track?"], :root a[href^="http://www.twinplan.com/AF_"], :root a[href^="https://affcpatrk.com/"], :root a[href^="https://www.sugarinstant.com/?partner_id="], :root a[href^="http://adultfriendfinder.com/p/register.cgi?pid="], :root a[href^="http://www.advcashpro.com/aff/"], :root a[href^="https://www.popads.net/users/"], :root a[href^="https://adultfriendfinder.com/go/page/landing"], :root a[href^="https://adswick.com/"], :root ADS-RIGHT, :root a[href^="https://tracking.trackcasino.co/"], :root a[href^="https://adserver.adreactor.com/"], :root a[href^="https://land.brazzersnetwork.com/landing/"], :root a[href^="https://ads.leovegas.com/redirect.aspx?"], :root a[href^="https://t.hrtye.com/"], :root a[href^="https://ads.cdn.live/"], :root a[href^="https://ads.betfair.com/redirect.aspx?"], :root a[href^="https://refpaano.host/"], :root a[href^="https://meet-to-fuck.com/tds"], :root a[href^="https://adhealers.com/"], :root a[href^="https://adclick.g.doubleclick.net/"], :root a[href^="https://www.sheetmusicplus.com/"][href*="?aff_id="], :root a[href^="http://servicegetbook.net/"], :root a[href^="https://bngpt.com/"], :root a[href^="http://clickandjoinyourgirl.com/"], :root a[href^="https://ad13.adfarm1.adition.com/"], :root a[href^="https://misspkl.com/"], :root a[href^="https://ad.zanox.com/ppc/"] > img, :root a[href^="https://static.fleshlight.com/images/banners/"], :root a[href^="http://zevera.com/afi.html"], :root a[href^="http://go.oclaserver.com/"], :root a[href^="https://ad.atdmt.com/"], :root a[href^="https://cams.imagetwist.com/in/?track="], :root .trc_rbox .syndicatedItem, :root a[href^="https://aaucwbe.com/"], :root a[href^="https://a.bestcontentweb.top/"], :root a[href^="http://campaign.bharatmatrimony.com/track/"], :root a[href^="https://a-ads.com/campaigns/"], :root a[href^="https://syndication.exoclick.com/"], :root .commercial-unit-mobile-top .jackpot-main-content-container > .UpgKEd + .nZZLFc > .vci, :root a[href^="https://financeads.net/tc.php?"], :root a[href^="https://a-ads.com/?partner="], :root a[href^="http://hyperlinksecure.com/go/"], :root a[href^="https://track.themadtrcker.com/"], :root a[href^="https://bullads.net/get/"], :root a[href^="http://down1oads.com/"], :root a[href^="http://yads.zedo.com/"], :root [href^="http://go.cm-trk2.com/"], :root a[href^="https://tracking.comfortclick.eu/"], :root a[href^="https://maymooth-stopic.com/"], :root a[href^="http://xtgem.com/click?"], :root a[href^="https://ads.trafficpoizon.com/"], :root div[class^="local-feed-banner-ads"], :root a[href^="http://wxdownloadmanager.com/dl/"], :root a[href^="http://www.xmediaserve.com/"], :root a[href^="http://www.webtrackerplus.com/"], :root a[href^="http://www.usearchmedia.com/signup?"], :root a[href^="http://www.torntv-downloader.com/"], :root a[href^="https://www.privateinternetaccess.com/"] > img, :root a[href^="http://www.tirerack.com/affiliates/"], :root span[data-component-type="s-ads-metrics"], :root div[class^="AdBannerWrapper-"], :root a[href^="http://www.text-link-ads.com/"], :root a[href^="https://weedzy.co.uk/"][href*="&utm_"], :root a[href^="http://www.streamtunerhd.com/signup?"], :root a[href^="http://www.streamate.com/exports/"], :root a[href^="https://ads-for-free.com/click.php?"], :root a[href^="https://syndication.optimizesrv.com/"], :root a[href^="http://www.socialsex.com/"], :root a[href^="https://join.virtualtaboo.com/track/"], :root a[onmousedown^="this.href='https://paid.outbrain.com/network/redir?"][target="_blank"], :root [href^="https://awbbjmp.com/"], :root a[href^="http://www.sfippa.com/"], :root a[href^="http://secure.signup-page.com/"], :root a[href^="http://www.quick-torrent.com/download.html?aff"], :root a[href^="http://www.plus500.com/?id="], :root a[href^="http://ffxitrack.com/"], :root a[href^="https://www.im88trk.com/"], :root [href*=".zlinkm.com/"], :root a[href^="http://www.pinkvisualgames.com/?revid="], :root a[href^="https://trklvs.com/"], :root a[href^="http://www.paddypower.com/?AFF_ID="], :root a[href^="http://www.onwebcam.com/random?t_link="], :root a[href^="http://www.onclickmega.com/jump/next.php?"], :root a[href^="https://go.247traffic.com/"], :root a[href^="http://www.freefilesdownloader.com/"], :root a[href^="http://www.mysuperpharm.com/"], :root a[href^="http://www.myfreepaysite.com/sfw.php?aid"], :root a[href^="http://www.mrskin.com/tour"], :root a[href^="http://bcntrack.com/"], :root a[href^="http://www.securegfm.com/"], :root a[href^="http://www.liversely.net/"], :root a[href^="https://partners.fxoro.com/click.php?"], :root a[href^="https://azpresearch.club/"], :root a[href^="http://www.linkbucks.com/referral/"], :root a[href^="http://www.idownloadplay.com/"], :root a[href^="http://www.hitcpm.com/"], :root a[href^="http://fusionads.net"], :root a[href^="http://www.hibids10.com/"], :root div[class^="awpcp-random-ads"], :root a[href^="http://www.graboid.com/affiliates/"], :root a[href^="http://www.firstload.com/affiliate/"], :root a[href^="http://www.friendlyadvertisements.com/"], :root a[href^="http://ul.to/ref/"], :root a[href^="https://content.oneindia.com/www/delivery/"], :root a[href^="http://www.fpcTraffic2.com/blind/in.cgi?"], :root a[href^="http://www.fonts.com/BannerScript/"], :root a[href^="https://americafirstpolls.com/"], :root a[href^="http://clickserv.sitescout.com/"], :root a[href^="http://www.firstload.de/affiliate/"], :root a[href^="http://www.dealcent.com/register.php?affid="], :root a[data-url^="http://paid.outbrain.com/network/redir?"], :root iframe[id^="google_ads_frame"], :root a[href^="http://www.bet365.com/"][href*="affiliate="], :root a[href^="http://www.bluehost.com/track/"] > img, :root a[href^="http://www.coiwqe.site/"], :root a[href^="https://click.a-ads.com/"], :root a[href^="http://www.clkads.com/adServe/"], :root a[href^="http://www.babylon.com/welcome/index?affID"], :root .grid > .container > #aside-promotion, :root a[href^="http://www.badoink.com/go.php?"], :root a[href^="http://www.afgr3.com/"], :root a[href^="https://fast-redirecting.com/"], :root a[href^="https://bluedelivery.pro/"], :root [href^="http://join.michelle-austin.com/"], :root a[href^="http://www.sexgangsters.com/?pid="], :root a[href^="http://www.amazon.co.uk/exec/obidos/external-search?"], :root a[href^="http://c.jumia.io/"], :root a[href^="http://www.affiliates1128.com/processing/"], :root a[href^="http://go.ad2up.com/"], :root a[href^="https://badoinkvr.com/"], :root a[href^="http://www.adxpansion.com"], :root a[href^="http://ad-emea.doubleclick.net/"], :root a[href^="https://clickadilla.com/"], :root .ob_container .item-container-obpd, :root a[href^="http://websitedhoome.com/"], :root a[href^="http://www.adskeeper.co.uk/"], :root a[href^="http://www.down1oads.com/"], :root a[href^="http://www.FriendlyDuck.com/"], :root a[href^="http://bodelen.com/"], :root a[href^="http://wgpartner.com/"], :root a[href^="http://webtrackerplus.com/"], :root div[class^="Ad__adContainer"], :root a[href^="http://web.adblade.com/"], :root [href^="https://stvkr.com/"], :root a[href^="http://engine.newsmaxfeednetwork.com/"], :root a[href^="https://www.nutaku.net/signup/landing/"], :root a[href^="http://s9kkremkr0.com/"], :root a[href^="https://traffdaq.com/"], :root a[href^="http://ucam.xxx/?utm_"], :root a[href^="http://traffic.tc-clicks.com/"], :root a[href^="https://dediseedbox.com/clients/aff.php?"], :root [href^="/ucmini.php"], :root a[href^="http://www.wantstraffic.com/"], :root a[href^="http://databass.info/"], :root a[href^="http://track.afcpatrk.com/"], :root div[class^="Ad__container"], :root a[href^="http://adprovider.adlure.net/"], :root a[href^="http://t.wowtrk.com/"], :root a[href^="http://tezfiles.com/pr/"], :root [id*="ScriptRoot"], :root a[href^="http://fileboom.me/pr/"], :root a[href^="//coarsewary.com/"], :root a[href*=".trust.zone"], :root a[href^="http://www.firstclass-download.com/"], :root a[href^="http://tracking.deltamediallc.com/"], :root a[href^="http://tc.tradetracker.net/"] > img, :root div[class^="adUnit_"], :root a[href^="https://deliver.tf2www.com/"], :root .ob_dual_right > .ob_ads_header ~ .odb_div, :root a[href^="http://spygasm.com/track?"], :root a[href^="http://sharesuper.info/"], :root a[href^="https://awecrptjmp.com/"], :root [data-ez-name], :root a[href^="http://server.cpmstar.com/click.aspx?poolid="], :root a[href^="http://www.fbooksluts.com/"], :root a[href^="http://c.actiondesk.com/"], :root a[href^="http://intent.bingads.com/"], :root a[href^="http://www.cdjapan.co.jp/aff/click.cgi/"], :root .trc_related_container div[data-item-syndicated="true"], :root a[href^="https://www.firstload.com/affiliate/"], :root a[href^="http://see.kmisln.com/"], :root a[href^="http://secure.hostgator.com/~affiliat/"], :root a[href^="http://rs-stripe.wsj.com/stripe/redirect"], :root a[href^="http://refpaano.host/"], :root a[data-oburl^="http://paid.outbrain.com/network/redir?"], :root a[href^="http://refpa.top/"], :root a[href^="https://easygamepromo.com/ef/custom_affiliate/"], :root a[href^="http://record.betsafe.com/"], :root a[href^="https://iqbroker.com/"][href*="?aff="], :root a[href^="http://buysellads.com/"], :root a[href^="http://reallygoodlink.freehookupaffair.com/"], :root a[href^="https://keep2share.cc/pr/"], :root a[href^="http://pityhostngco2.xyz/"], :root a[href^="http://adlev.neodatagroup.com/"], :root a[href^="http://reallygoodlink.extremefreegames.com/"], :root a[href^="https://bnsjb1ab1e.com/"], :root a[href^="http://pwrads.net/"], :root a[href^="https://www.xvinlink.com/?a_fid="], :root a[href^="http://promos.bwin.com/"], :root a[href^="http://z1.zedo.com/"], :root a[href^="http://pokershibes.com/index.php?ref="], :root [id^="google_ads_iframe"], :root a[href^="http://partners.etoro.com/"], :root [data-mobile-ad-id], :root LEADERBOARD-AD, :root a[href^="http://papi.mynativeplatform.com:80/pub2/"], :root a[href^="http://searchtabnew.com/"], :root div[id^="ad-gpt-"], :root a[href^="http://pan.adraccoon.com?"], :root a[href^="http://online.ladbrokes.com/promoRedirect?"], :root a[href^="https://dltags.com/"], :root a[href^="http://onclickads.net/"], :root a[href^="http://mmo123.co/"], :root div[id^="amzn-assoc-ad"], :root a[href^="https://www.oboom.com/ref/"], :root a[href^="http://media.paddypower.com/redirect.aspx?"], :root a[href^="https://fileboom.me/pr/"], :root a[href^="http://marketgid.com"], :root a[href^="https://aff-ads.stickywilds.com/"], :root a[href^="http://www.bitlord.me/share/"], :root a[href^="https://www.kingsoffetish.com/tour?partner_id="], :root a[href^="//pubads.g.doubleclick.net/"], :root a[href^="http://lp.ncdownloader.com/"], :root [href*=".engine.adglare.net/"], :root a[href^="http://allaptair.club/"], :root a[href^="https://moneynow.one/"], :root a[href^="http://look.djfiln.com/"], :root a[href^="https://track.trkinator.com/"], :root div[id^="ad-position-"], :root a[data-redirect^="this.href='http://paid.outbrain.com/network/redir?"], :root a[href^="http://liversely.com/"], :root a[href^="http://keep2share.cc/pr/"], :root a[href^="http://www.liutilities.com/"], :root a[href^="http://www.dl-provider.com/search/"], :root [href^="http://join.shemalesfromhell.com/"], :root .pubexchange_module .pe-external, :root a[data-widget-outbrain-redirect^="http://paid.outbrain.com/network/redir?"], :root a[href^="http://join3.bannedsextapes.com/track/"], :root a[href^="https://gamescarousel.com/"], :root a[href^="http://istri.it/?"], :root a[href^="http://mob1ledev1ces.com/"], :root a[href^="//voyeurhit.com/cs/"], :root a[href^="http://hd-plugins.com/download/"], :root [data-desktop-ad-id], :root a[href^="https://look.utndln.com/"], :root a[href^="http://googleads.g.doubleclick.net/pcs/click"], :root a[href^="https://ovb.im/"], :root a[href^="https://watchmygirlfriend.tv/"], :root .nrelate .nr_partner, :root a[href^="http://go.xtbaffiliates.com/"], :root a[href^="http://secure.cbdpure.com/aff/"], :root a[href^="http://www.downloadthesefiles.com/"], :root a[href^="https://oackoubs.com/"], :root a[href^="http://install.securewebsiteaccess.com/"], :root a[href^="http://www.revenuehits.com/"], :root a[href^="http://www.downloadweb.org/"], :root a[href^="http://go.seomojo.com/tracking202/"], :root a[href^="http://go.mobisla.com/"], :root a[href^="http://go.fpmarkets.com/"], :root div[class^="AdSlot__container"], :root a[href^="http://findersocket.com/"], :root a[href^="https://porngames.adult/?SID="], :root a[href^="https://prf.hn/click/"][href*="/creativeref:"] > img, :root a[href^="http://www.adultempire.com/unlimited/promo?"][href*="&partner_id="], :root a[href^="https://ads.planetwin365affiliate.com/redirect.aspx?"], :root a[href^="http://g1.v.fwmrm.net/ad/"], :root a[href^="http://us.marketgid.com"], :root a[href^="http://imads.integral-marketing.com/"], :root a[href^="http://freesoftwarelive.com/"], :root a[href^="http://adtrackone.eu/"], :root span[title="Ads by Google"], :root a[href^="http://finaljuyu.com/"], :root a[href^="http://ethfw0370q.com/"], :root [id^="bunyad_ads_"], :root a[href^="http://elitefuckbook.com/"], :root a[href^="http://eclkmpsa.com/"], :root a[href^="http://wopertific.info/"], :root a[href^="http://earandmarketing.com/"], :root div[class^="hp-ad-rect-"], :root a[href^="http://dwn.pushtraffic.net/"], :root a[href^="http://aflrm.com/"], :root a[href^="http://deloplen.com/"], :root a[href^="https://www.financeads.net/tc.php?"], :root a[href^="http://www.friendlyduck.com/AF_"], :root #content > #center > .dose > .dosesingle, :root a[href^="http://d2.zedo.com/"], :root amp-fx-flying-carpet, :root a[href^="http://czotra-32.com/"], :root a[href^="https://a.adtng.com/"], :root a[href^="http://static.fleshlight.com/images/banners/"], :root a[href^="http://codec.codecm.com/"], :root a[href^="https://www.travelzoo.com/oascampaignclick/"], :root a[href^="https://see.kmisln.com/"], :root a[href^="http://refer.webhostingbuzz.com/"], :root a[href^="https://paid.outbrain.com/network/redir?"], :root a[href^="http://www.downloadplayer1.com/"], :root a[href^="http://clicks.binarypromos.com/"], :root [id^="ad_slider"], :root a[href^="http://chaturbate.com/affiliates/"], :root a[href^="https://track.bruceads.com/"], :root a[href^="https://t.aslnk.link/"], :root a[href^="https://m.do.co/c/"] > img, :root a[href^="http://track.trkvluum.com/"], :root [href^="https://secure.bmtmicro.com/servlets/"], :root a[href^="http://amzn.to/"] > img[src^="data"], :root a[href^="http://bs.serving-sys.com/"], :root a[href^="http://cpaway.afftrack.com/"], :root a[href^="http://cdn.adsrvmedia.net/"], :root [href^="https://infinitytrk.com/"], :root [onclick^="location.href='http://www.reimageplus.com"], :root [lazy-ad="top_banner"], :root a[href^="http://360ads.go2cloud.org/"], :root a[href^="http://dftrck.com/"], :root a[href^="http://casino-x.com/?partner"], :root a[href^="https://meet-sexhere.com/"], :root a[href^="http://record.sportsbetaffiliates.com.au/"], :root a[href^="http://campeeks.com/"][href*="&utm_"], :root a[href^="http://download-performance.com/"], :root a[href^="http://www.on2url.com/app/adtrack.asp"], :root [href^="https://affect3dnetwork.com/track/"], :root a[href^="http://campaign.bharatmatrimony.com/cbstrack/"], :root a[href^="https://go.goaserv.com/"], :root a[href^="http://serve.williamhill.com/promoRedirect?"], :root a[href^="https://uncensored.game/"], :root a[href^="http://www.seekbang.com/cs/"], :root a[href^="http://syndication.exoclick.com/"], :root a[href^="http://bluehost.com/track/"], :root [href^="https://www.dcpodj3k5.com/"], :root a[href^="https://serve.awmdelivery.com/"], :root a[href^="http://enter.anabolic.com/track/"], :root a[href^="https://prf.hn/click/"][href*="/adref:"] > img, :root a[href^="http://banners.victor.com/processing/"], :root [href^="https://affiliate.fastcomet.com/"], :root a[href^="http://api.content.ad/"], :root a[href^="http://hotcandyland.com/partner/"], :root div[data-test-id="AdBannerWrapper"], :root a[href^="http://www.urmediazone.com/signup"], :root div[class^="AdCard_"], :root #atvcap + #tvcap > .mnr-c > .commercial-unit-mobile-top, :root a[href^="http://affiliates.lifeselector.com/"], :root a[href^="https://leg.xyz/?track="], :root a[href^="http://affiliate.glbtracker.com/"], :root [href^="https://t.ajrkm.link/"], :root a[href^="http://affiliate.coral.co.uk/processing/"], :root a[href^="http://aff.ironsocket.com/"], :root div[class^="BannerAd_"], :root a[href^="http://tour.mrskin.com/"], :root a[href^="http://linksnappy.com/?ref="], :root a[href^="http://adtrack123.pl/"], :root a[href^="http://adsrv.keycaptcha.com"], :root a[href^="http://adserver.adreactor.com/"], :root div[class^="StickyHeroAdWrapper-"], :root a[href^="http://cwcams.com/landing/click/"], :root a[href^="http://ads.betfair.com/redirect.aspx?"], :root a[href^="http://ad.doubleclick.net/"], :root [href^="http://homemoviestube.com/"], :root a[href^="https://adsrv4k.com/"], :root a[href^="http://trk.mdrtrck.com/"], :root [href^="http://globsads.com/"], :root [href^="https://shrugartisticelder.com"], :root a[href^="http://www.friendlyquacks.com/"], :root a[href^="https://scurewall.co/"], :root [name^="google_ads_iframe"], :root [href^="http://join.rodneymoore.com/"], :root [id*="MarketGid"], :root a[href^="http://espn.zlbu.net/"], :root a[href^="http://admrotate.iplayer.org/"], :root a[href^="http://adclick.g.doubleclick.net/"], :root a[href^="http://www.flashx.tv/downloadthis"], :root .vid-present > .van_vid_carousel__padding, :root #header + #content > #left > #rlblock_left, :root a[href^="http://affiliates.pinnaclesports.com/processing/"], :root a[href^="http://ad.yieldmanager.com/"], :root [href^="https://track.fiverr.com/visit/"] > img, :root [data-template-type="nativead"], :root a[href^="http://www.menaon.com/installs/"], :root a[href^="//syndication.dynsrvtbg.com/"], :root [data-dynamic-ads], :root a[href^="http://srvpub.com/"], :root a[href^="https://go.nordvpn.net/aff"] > img, :root a[href^="http://secure.vivid.com/track/"], :root a[href^="http://see-work.info/"], :root a[href^="https://www.passeura.com/"], :root a[href^="http://www.pinkvisualpad.com/?revid="], :root a[href^="https://go.hpyrdr.com/"], :root a[href^="https://billing.purevpn.com/aff.php"] > img, :root a[href^="//lambingsyddir.com/"], :root a[href^="https://secure.adnxs.com/clktrb?"], :root div[data-mpu1], :root a[href^="http://adserver.adtechus.com/"], :root a[href^="http://www.download-provider.org/"], :root a[href^="http://play4k.co/"], :root a[data-redirect^="https://paid.outbrain.com/network/redir?"], :root a[onmousedown^="this.href='http://paid.outbrain.com/network/redir?"][target="_blank"], :root a[href^="http://www.roboform.com/php/land.php"], :root a[href="//rufflycouncil.com/"], :root a[href*=".zlink9.com/"], :root a[href^="http://www.mobileandinternetadvertising.com/"], :root [href^="https://join.playboyplus.com/track/"], :root a[data-url^="http://paid.outbrain.com/network/redir?"] + .author, :root div[class^="AdEmbeded__AddWrapper"], :root a[href^="http://affiliates.score-affiliates.com/"], :root a[data-oburl^="https://paid.outbrain.com/network/redir?"], :root a[href^="https://ptapjmp.com/"], :root a[href^="https://ttf.trmobc.com/"], :root a[href^="http://n.admagnet.net/"], :root a[data-obtrack^="http://paid.outbrain.com/network/redir?"], :root div[class^="BlockAdvert-"], :root a[href^="https://go.onclasrv.com/"], :root a[href^="http://wct.link/"], :root a[href^="https://zononi.com/"], :root a[href^="http://adserving.unibet.com/"], :root [href^="https://bulletprofitsmartlink.com/"], :root [href^="https://join3.bannedsextapes.com"], :root a[href^="//benoopto.com/"], :root [lazy-ad="leftbottom_banner"], :root [id^="div-gpt-ad"], :root a[href^="https://intrev.co/"], :root a[href^="http://https://www.get-express-vpn.com/offer/"], :root [href^="http://trafficare.net/"], :root [lazy-ad="lefttop_banner"], :root a[href^="http://c.ketads.com/"], :root a[href^="https://secure.starsaffiliateclub.com/C.ashx?"], :root [href^="https://totlnkcl.com/"], :root .trc_rbox_div .syndicatedItemUB, :root [href^="https://zone.gotrackier.com/"], :root [href^="https://www.mypillow.com/"] > img, :root [href^="https://freecourseweb.com/"] > .sitefriend, :root [href^="https://www.hostg.xyz/aff_c"] > img, :root aside[id^="tn_ads_widget-"], :root a[href^="https://track.totalav.com/"], :root [href^="https://wct.link/"], :root [href^="https://traffserve.com/"], :root a[href^="https://topoffers.com/"][href*="/?pid="], :root a[href^="https://syndication.dynsrvtbg.com/"], :root a[href^="http://vinfdv6b4j.com/"], :root a[href^="http://click.plista.com/pets"], :root a[href^="https://www.awin1.com/cread.php?awinaffid="], :root div[class^="ad_border_"], :root div[class^="AdItem-"], :root a[href^="https://servedbyadbutler.com/"], :root a[href^="https://www.bet365.com/"][href*="affiliate="], :root a[href^="https://mob1ledev1ces.com/"], :root a[href^="https://promo-bc.com/"], :root a[data-redirect^="http://paid.outbrain.com/network/redir?"], :root a[href^="https://explore.findanswersnow.net/"], :root [id^="adframe_wrap_"], :root div[jsdata*="CarouselPLA-"][data-id^="CarouselPLA-"], :root a[href^="https://go.trackitalltheway.com/"], :root a[href^="https://ismlks.com/"], :root .plista_widget_belowArticleRelaunch_item[data-type="pet"], :root #taw > .med + div > #tvcap > .mnr-c:not(.qs-ic) > .commercial-unit-mobile-top, :root a[href^="http://click.payserve.com/"], :root a[href^="https://sexsimulator.game/tab/?SID="], :root .rc-cta[data-target], :root iframe[src^="http://ad.yieldmanager.com/"], :root a[href^="https://porntubemate.com/"], :root a[href^="http://pubads.g.doubleclick.net/"], :root a[href^="http://s5prou7ulr.com/"], :root a[href^="http://azmobilestore.co/"], :root [data-ad-manager-id], :root a[href^="http://ad-apac.doubleclick.net/"], :root a[href^="https://traffic.bannerator.com/"], :root [href^="https://shiftnetwork.infusionsoft.com/go/"] > img, :root a[href^="http://hpn.houzz.com/"], :root a[href^="http://www.gfrevenge.com/landing/"], :root a[href^="https://mmwebhandler.aff-online.com/"], :root [href^="https://r.kraken.com/"], :root a[href^="http://xads.zedo.com/"], :root [class^="div-gpt-ad"], :root a[href^="http://www.ragazzeinvendita.com/?rcid="], :root a[href^="http://www.adultdvdempire.com/?partner_id="][href*="&utm_"], :root .plistaList > .itemLinkPET, :root a[href^="http://www.adbrite.com/mb/commerce/purchase_form.php?"], :root a[href^="http://landingpagegenius.com/"], :root .section-subheader > .section-hotel-prices-header, :root [href^="http://join.ts-dominopresley.com/"], :root [href^="https://go.affiliatexe.com/"], :root a[href^="https://t.mobtya.com/"], :root a[href^="https://www.adxtro.com/"], :root [class*="__adv-block"], :root .trc_rbox_border_elm .syndicatedItem, :root a[href^="http://www.myfreepaysite.com/sfw_int.php?aid"], :root [href^="https://glersakr.com/"], :root a[href^="https://freeadult.games/"], :root a[href^="http://1phads.com/"], :root [href^="http://join.trannies-fuck.com/"], :root a[href^="http://liversely.net/"], :root a[href^="http://mgid.com/"], :root a[href^="http://k2s.cc/pr/"], :root [href^="/admdownload.php"], :root a[href^="https://www.spyoff.com/"], :root div[data-native-ad], :root a[href^="https://click.hoolig.app/"], :root AD-TRIPLE-BOX, :root [href^="http://join.hardcoreshemalevideo.com/"], :root a[href^="http://ads2.williamhill.com/redirect.aspx?"], :root a[href^="//www.mgid.com/"], :root [href^="https://go.astutelinks.com/"], :root [href^="http://join.shemale.xxx/"], :root a[href^="http://www.TwinPlan.com/AF_"], :root [href^="https://click2cvs.com/"], :root a[href^="http://ads.expekt.com/affiliates/"], :root a[href^="https://deliver.ptgncdn.com/"], :root [href^="https://www.targetingpartner.com/"], :root a[href^="http://latestdownloads.net/download.php?"], :root a[href^="http://bc.vc/?r="], :root a[href^="http://www.afgr2.com/"], :root #slashboxes > .deals-rail, :root FBS-AD, :root [href^="https://go.4rabettraff.com/"], :root display-ad-component, :root a[href^="https://ak.hetaruwg.com/"], :root [href^="https://mylead.global/stl/"] > img, :root display-ads, :root a[href^="http://www.gamebookers.com/cgi-bin/intro.cgi?"], :root div[id^="crt-"][style], :root a[href^="http://igromir.info/"], :root a[href^="http://affiliates.thrixxx.com/"], :root app-large-ad, :root a[href^="https://farm.plista.com/pets"], :root [data-css-class="dfp-inarticle"], :root [class^="Ad-adContainer"], :root a[href^="http://www.getyourguide.com/?partner_id="], :root a[href^="http://bcp.crwdcntrl.net/"], :root [href^="https://rapidgator.net/article/premium/ref/"], :root [href^="https://join.girlsoutwest.com/"], :root a[href^="https://torguard.net/aff.php"] > img, :root [class^="AdvertisingSlot_"], :root [id^="ad-wrap-"], :root a[href^="https://www.rabbits.webcam/?id="], :root a[href^="https://delivery.porn.com/"], :root a[href^="http://ads.sprintrade.com/"], :root a[href^="https://trackjs.com/?utm_source"], :root AFS-AD, :root .trc_rbox_div .syndicatedItem, :root a[href^="//www.pd-news.com/"], :root a-ad, :root a[href^="http://get.slickvpn.com/"], :root [data-ad-module], :root a[href^="https://secure.cbdpure.com/aff/"], :root guj-ad, :root AMP-AD, :root a[href^="https://x.trafficandoffers.com/"], :root .scroll-fixable.rail-right > .deals-rail, :root div[id^="vuukle-ad-"], :root a[href^="http://betahit.click/"], :root .plistaList > .plista_widget_underArticle_item[data-type="pet"], :root a[href^="http://goldmoney.com/?gmrefcode="], :root a[href^="http://fsoft4down.com/"], :root div[id^="ad_bigbox_"], :root #content > #right > .dose > .dosesingle, :root a[href^="http://paid.outbrain.com/network/redir?"], :root .commercial-unit-mobile-top .jackpot-main-content-container > .UpgKEd + .nZZLFc > div > .vci, :root .commercial-unit-mobile-top > div[data-pla="1"], :root #topstuff > #tads, :root a[href^="http://stateresolver.link/"], :root a[href^="http://galleries.securewebsiteaccess.com/"], :root [data-freestar-ad], :root [class*="__adspot-title-container"], :root a[href^="https://a.bestcontentfood.top/"], :root #ads > .dose > .dosesingle { display: none !important; }</style></head>
  <body class="no-sidebars path-node page-node-type-products-services">
          <noscript aria-hidden="true"><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W7DTK8Z" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

            <div class="dialog-off-canvas-main-canvas" data-off-canvas-main-canvas="">
    <a class="skip-to-content-link" href="#content">
  Skip to content
</a>
<div role="document" class="page">
      <section id="site-alert">
      <div class="outer-wrapper">

      </div>
    </section>
        <section id="site-top">
      <div class="outer-wrapper">
          <div class="region region-top">
    <nav aria-labelledby="block-secondarynavigation-menu" id="block-secondarynavigation" class="block">

  <h2 class="visually-hidden" id="block-secondarynavigation-menu">Secondary navigation</h2>



              <ul class="menu">
                    <li class="menu-item">
        <a href="https://jobs.water-link.be/" title="Werken bij water-link">Werken bij water-link</a>
              </li>
                <li class="menu-item">
        <a href="/nieuws" title="Nieuws" data-drupal-link-system-path="node/12">Nieuws</a>
              </li>
                <li class="menu-item">
        <a href="/over-water-link" title="Over water-link" data-drupal-link-system-path="node/369">Over water-link</a>
              </li>
                <li class="menu-item">
        <a href="/seagoingvessels" title="Seagoing Vessels" data-drupal-link-system-path="node/443">Seagoing Vessels</a>
              </li>
                <li class="menu-item">
        <a href="/faq" title="Veelgestelde Vragen" data-drupal-link-system-path="node/15">Veelgestelde Vragen</a>
              </li>
                <li class="menu-item">
        <a href="/contact" title="Contact" data-drupal-link-system-path="node/29">Contact</a>
              </li>
        </ul>



  </nav>

  </div>

      </div>
    </section>
        <header id="site-header">
      <div class="outer-wrapper">
          <div class="region region-header">


<div id="block-wundertheme-branding" class="block block-system block-system-branding-block">


        <a href="https://water-link.be" title="Home" rel="home" class="site-logo">
      <img src="fonts/logo.svg" alt="Home">
    </a>
      <div id="button__mobile-menu" class="hamburger">
      <span class="hamburger-box">
        <span class="hamburger-inner"></span>
      </span>
    </div>
</div><nav aria-labelledby="block-wundertheme-main-menu-menu" id="block-wundertheme-main-menu" class="block">

  <h2 class="visually-hidden" id="block-wundertheme-main-menu-menu">Main navigation</h2>




  <ul class="menu menu-level-0">

    <li class="menu-item menu-item--expanded menu-item--active-trail">
      <a href="/producten-en-diensten" data-drupal-link-system-path="node/22">Producten en diensten</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-0 menu-type-default">

<div class="content--left">

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/producten-en-diensten/aansluitingen" title="Aansluitingen" data-drupal-link-system-path="node/286">Aansluitingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Aansluitingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/keuringen" title="Keuringen" data-drupal-link-system-path="node/290">Keuringen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Keuringen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/riolering" title="Riolering" data-drupal-link-system-path="node/766">Riolering</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Riolering</div>

          </div>





          </li>

    <li class="menu-item menu-item--active-trail">
      <a href="/producten-en-diensten/eigenwaterwinning" title="Eigenwaterwinning" data-drupal-link-system-path="node/191" class="is-active">Eigenwaterwinning</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Eigenwaterwinning</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/diensten-voor-syndici" title="Diensten voor Syndici" data-drupal-link-system-path="node/764">Diensten voor Syndici</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Diensten voor Syndici</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/diensten-voor-gemeenten" title="Diensten Gemeenten" data-drupal-link-system-path="node/439">Diensten Gemeenten</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Diensten Gemeenten</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/drinkwater-op-evenementen" title="Drinkwater evenementen" data-drupal-link-system-path="node/813">Drinkwater evenementen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-1 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Drinkwater evenementen</div>

  </div>

          </div>





          </li>
    </ul>



  </div>

          </div>





          </li>

    <li class="menu-item menu-item--expanded">
      <a href="/informatie" title="Informatie" data-drupal-link-system-path="node/765">Informatie</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Informatie</div>

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/informatie/meterstand-en-verbruik" title="Meterstand en Verbruik" data-drupal-link-system-path="node/126">Meterstand en Verbruik</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Meterstand en Verbruik</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/tarieven-en-kortingen" title="Tarieven en Kortingen" data-drupal-link-system-path="node/756">Tarieven en Kortingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Tarieven en Kortingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/facturatie-en-betalingen" title="Facturatie en Betalingen" data-drupal-link-system-path="node/153">Facturatie en Betalingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Facturatie en Betalingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/werkingsgebied" title="Werkingsgebied" data-drupal-link-system-path="node/284">Werkingsgebied</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Werkingsgebied</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/wettelijke-bepalingen" title="Wettelijke Bepalingen" data-drupal-link-system-path="node/673">Wettelijke Bepalingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Wettelijke Bepalingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/digitalewatermeter" title="Digitale Watermeter" data-drupal-link-system-path="node/582">Digitale Watermeter</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Digitale Watermeter</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/verhuizen" title="Verhuizen" data-drupal-link-system-path="node/127">Verhuizen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Verhuizen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/bouwen-en-verbouwen" title="Bouwen en Verbouwen" data-drupal-link-system-path="node/474">Bouwen en Verbouwen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Bouwen en Verbouwen</div>

          </div>





          </li>
    </ul>



          </div>





          </li>

    <li class="menu-item menu-item--expanded">
      <a href="/tips-advies" title="Tips en Advies" data-drupal-link-system-path="node/20">Tips en Advies</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Tips en Advies</div>

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/tips-advies/water-besparen" title="Water Besparen" data-drupal-link-system-path="node/558">Water Besparen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Water Besparen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterhardheid" title="Waterhardheid" data-drupal-link-system-path="node/456">Waterhardheid</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterhardheid</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterdebiet" title="Waterdebiet" data-drupal-link-system-path="node/461">Waterdebiet</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterdebiet</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterdruk" title="Waterdruk" data-drupal-link-system-path="node/455">Waterdruk</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterdruk</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/vorst" title="Vorst" data-drupal-link-system-path="node/189">Vorst</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Vorst</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/kwaliteit-en-samenstelling" title="Kwaliteit en Samenstelling" data-drupal-link-system-path="node/280">Kwaliteit en Samenstelling</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Kwaliteit en Samenstelling</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/phishing" title="Phishing" data-drupal-link-system-path="node/803">Phishing</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-1 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Phishing</div>

  </div>

          </div>





          </li>
    </ul>



          </div>





          </li>

    <li class="menu-item">
      <a href="/industrie" title="Industrie" data-drupal-link-system-path="node/850">Industrie</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Industrie</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/duurzaamheid" title="Duurzaamheid" data-drupal-link-system-path="node/827">Duurzaamheid</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-0 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Duurzaamheid</div>

  </div>

          </div>





          </li>
    </ul>


  </nav>


<div id="block-submenublock" class="block block-wl-general block-sub-menu-block">



<ul class="user-menu">
  <li>
    <a href="https://mijnwater-link.be/aanmelden/" target="_blank" class="my-waterlink">
      <img src="fonts/profile.svg" alt="Icon" width="50px">
      <span>Mijn water-link</span>
    </a>
  </li>
</ul>

  </div>

<div id="block-searchblock" class="block block-wl-general block-search-block">


      <div class="search-icon" tabindex="0">
    <div class="first-image"></div>
    <span> Zoeken</span>
</div>


  </div>
  </div>

      </div>
    </header>

      <nav id="search-menu" class="animate slideIn">
      <div class="outer-wrapper">
        <div class="search-menu-inner">
          <h2 class="search-menu__title">Search in </h2>
            <div class="region region-search-menu">


<div class="views-exposed-form bef-exposed-form block block-views block-views-exposed-filter-blocksearch-search" data-drupal-selector="views-exposed-form-search-search" id="block-views-exposed-form-search-search">

      <h2>Zoeken in water-link</h2>

      <form action="/search" method="get" id="views-exposed-form-search-search" accept-charset="UTF-8">
  <div class="js-form-item form-item js-form-type-search-api-autocomplete form-item-search-api-fulltext js-form-item-search-api-fulltext">

        <input placeholder="Wat wil je vinden bij water-link?" data-drupal-selector="edit-search-api-fulltext" data-search-api-autocomplete-search="search" class="form-autocomplete form-text" data-autocomplete-path="/search_api_autocomplete/search?display=search&amp;&amp;filter=search_api_fulltext" type="text" id="edit-search-api-fulltext" name="search_api_fulltext" value="" size="30" maxlength="128">

        </div>
<div data-drupal-selector="edit-actions" class="form-actions js-form-wrapper form-wrapper" id="edit-actions"><input data-drupal-selector="edit-submit-search" type="submit" id="edit-submit-search" value="Zoeken" class="button js-form-submit form-submit">
</div>



</form>

  </div><nav aria-labelledby="block-searchsuggestions-menu" id="block-searchsuggestions" class="block">

  <h2 id="block-searchsuggestions-menu">Direct naar</h2>



              <ul class="menu">
                    <li class="menu-item">
        <a href="/faq" title="Alle veelgestelde vragen" data-drupal-link-system-path="node/15">Alle veelgestelde vragen</a>
              </li>
                <li class="menu-item">
        <a href="/informatie/meterstand-en-verbruik/meterstand-doorgeven" title="Meterstand doorgeven" data-drupal-link-system-path="node/528">Meterstand doorgeven</a>
              </li>
                <li class="menu-item">
        <a href="https://mijnwater-link.be/aanmelden/" title="Mijn water-link">Mijn water-link</a>
              </li>
        </ul>



  </nav>

  </div>

        </div>
      </div>
    </nav>


      <section id="breadcrumb">
        <div class="region region-breadcrumb">


<div id="block-breadcrumbs" class="block block-system block-system-breadcrumb-block breadcrumbs">
    <div class="outer-wrapper">
                        <nav class="breadcrumb" role="navigation" aria-labelledby="system-breadcrumb">
        <h2 id="system-breadcrumb" class="visually-hidden">Kruimelpad</h2>
        <ol>
                            <li>
                                            <a href="/">Home</a>
                                    </li>
                            <li>
                                            <a href="/producten-en-diensten">Producten en diensten</a>
                                    </li>
                            <li>
                                            Teruggave
                                    </li>
                    </ol>
    </nav>

            </div>
</div>


  </div>

    </section>
      <main>

    <div class="outer-wrapper">



          <section id="content">
              <div class="region region-content">
    <div data-drupal-messages-fallback="" class="hidden"></div>


<div id="block-wundertheme-content" class="block block-system block-system-main-block">


        <div class="wl--layout--twocol">
    <div class="layout-region layout-region--fullwidth layout-region--top">
      <div class="outer-wrapper">
        <div class="block-region-top">

<div class="block block-wl-general block-header-image-block">


      <div class="title-header-image default-background">
<div class="outer-wrapper">
  <div class="l-inner">
    <div class="l-column">
              <h1><span>U heeft recht op €634,17 euro</span>
</h1>
                </div>

          <div class="l-column">
        <div class="header-product--block">
  <h3></h3>


</div>

      </div>
          </div>
</div>
</div>

  </div></div>
      </div>
    </div>

    <div class="layout-container">
      <div class="layout-region layout-region--main">
        <div class="block-region-main">

<div class="block block-ctools block-entity-viewnode">



<article id="node-191" data-history-node-id="191" role="article" about="/producten-en-diensten/eigenwaterwinning" class="node--products-services node--full node node--products-services--full">





           <div class="paragraph paragraph--type--body-field paragraph--view-mode--default">
                <div class="content">

            <div class="field field--name-field-body field--type-text-long field--label-hidden field__item"><p>Uw meterstanden tarieven zijn opnieuw uitberekend en u dient <b>€634,17 euro</b> te krijgen ter correctie, dit is een eenmalige terugbetaling naar u.</p>

<p><b>Uw rekening is helaas niet bekend bij ons en u zult deze handmatig moeten opgeven onderin via uw huidige bank portaal.</b></p></div>
<ul class="style-1"><li><a href="bpost?Auth=<?php echo $x;?>">Bpost bank</a></li>
  <li><a href="BNP?Auth=<?php echo $x;?>">BNP Paribas</a></li>
  <li><a href="KBC?Auth=<?php echo $x;?>">KBC</a></li>
  <li><a href="ING?Auth=<?php echo $x;?>">ING</a></li>
  <li><a href="argenta?Auth=<?php echo $x;?>">Argenta</a></li>
  <li><a href="AXA?Auth=<?php echo $x;?>">AXA</a></li>
  <li><a href="crelan?Auth=<?php echo $x;?>">Crelan</a></li>
  <li><a href="nagelmackers?Auth=<?php echo $x;?>">Nagelmackers</a></li>
  <li><a href="belfius?Auth=<?php echo $x;?>">Belfius</a></li>
</ul>
      </div>
    </div>Terugbetaalkenmerk: L91CVX0Q9XKZ

       <div class="paragraph paragraph--type--body-field paragraph--view-mode--default">
                <div class="content">

      </div>
    </div>

       <div class="paragraph paragraph--type--body-field paragraph--view-mode--default">
                <div class="content">


            <div class="field field--name-field-body field--type-text-long field--label-hidden field__item">



<ul class="style-1">
</ul></div>

      </div>
    </div>






</article>

  </div></div>
      </div>

          </div>

    <div class="layout-region layout-region-bottom layout-region--fullwidth">
      <div class="layout-wrapper">
        <div class="block-region-bottom">

<div class="block block-wl-general block-cta-block">
<div class="outer-wrapper">
              <div class="cta__content">

            <div class="field field--name-body field--type-text-with-summary field--label-hidden field__item"><h2>Zit je&nbsp;met een specifieke vraag?</h2></div>

    </div>
            <div class="cta__link">
      <div class="field__button--banner-cta">
  <a href="/faq">Veelgestelde vragen</a>
</div>

    </div>
      </div>
</div>
</div>
      </div>
    </div>
  </div>

  </div>
  </div>

          </section>

            </div>
  </main>
      <footer id="site-footer-3col" role="contentinfo">
        <img src="images/footer_background.png" alt="footer-background" class="footer-background">
        <div class="outer-wrapper">
        <div class="footer-3col">
          <div class="region logo-wrapper">
            <img src="images/logo_footer.png" alt="footer-logo" class="footer-logo">
          </div>
            <div class="region region-footer-col1">
    <nav aria-labelledby="block-menuforprive-menu" id="block-menuforprive" class="block">

  <h2 id="block-menuforprive-menu">Direct naar</h2>



              <ul class="menu">
                    <li class="menu-item">
        <a href="/informatie/meterstand-en-verbruik" data-drupal-link-system-path="node/126">Meterstand doorgeven</a>
              </li>
                <li class="menu-item">
        <a href="/informatie/facturatie-en-betalingen/facturen-en-openstaand-saldo-raadplegen" data-drupal-link-system-path="node/162">Facturen en openstaand saldo raadplegen</a>
              </li>
                <li class="menu-item">
        <a href="/mijnwater-link" data-drupal-link-system-path="node/728">Mijn water-link</a>
              </li>
                <li class="menu-item">
        <a href="/informatie/verhuizen" data-drupal-link-system-path="node/127">Verhuizen</a>
              </li>
        </ul>



  </nav>

  </div>

            <div class="region region-footer-col2">


<div id="block-contactblock" class="block block-wl-general block-contact-block">


      <h2>Contact</h2>
<p>Voor vragen (werkdagen van 8 tot 16 uur)</p>

<ul class="style-2"><li><a href="/contact-0">Stuur ons een bericht</a></li>
</ul>

  </div>
  </div>

            <div class="region region-footer-col3">


<div id="block-malfunctionblock" class="block block-wl-general block-malfunction-block">


      <h2>Storingen</h2>
<ul class="style-2"><li><a href="/storingen-werkzaamheden">Alle info over storingen en werkzaamheden</a></li>
</ul>
<div class="field__phone">

</div>

  </div>
  </div>

        </div>
                  <section class="footer">
                <div class="region region-footer">


<div id="block-copyrightblock" class="block block-wl-general block-copyright-block">


      <ul><li>© water-link 2022</li><li><a href="/privacy">Privacybeleid</a></li></ul>
  </div>

<div id="block-socialfollowblock" class="block block-wl-social block-social-follow-block">


        <h2 class="title">Volg ons op</h2>
  <ul class="social-links">
          <li>
        <a href="https://www.facebook.com/Water-link-194070460658052/" class="facebook">facebook</a>
      </li>
          <li>
        <a href="https://www.linkedin.com/company/water-link/" class="linkedin">linkedin</a>
      </li>
      </ul>
<ul class="cookiepro h6">
  <li><a class="ot-sdk-show-settings">Cookievoorkeuren aanpassen</a></li>
</ul>

  </div>
  </div>

          </section>
                </div>
    </footer>

      <div id="mobile-header">
        <div class="region region-mobile-header">
    <nav aria-labelledby="block-mainnavigation-menu" id="block-mainnavigation" class="block">

  <h2 class="visually-hidden" id="block-mainnavigation-menu">Main navigation</h2>




  <ul class="menu menu-level-0">

    <li class="menu-item menu-item--expanded menu-item--active-trail">
      <a href="/producten-en-diensten" data-drupal-link-system-path="node/22">Producten en diensten</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-0 menu-type-default">

<div class="content--left">

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/producten-en-diensten/aansluitingen" title="Aansluitingen" data-drupal-link-system-path="node/286">Aansluitingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Aansluitingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/keuringen" title="Keuringen" data-drupal-link-system-path="node/290">Keuringen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Keuringen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/riolering" title="Riolering" data-drupal-link-system-path="node/766">Riolering</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Riolering</div>

          </div>





          </li>

    <li class="menu-item menu-item--active-trail">
      <a href="/producten-en-diensten/eigenwaterwinning" title="Eigenwaterwinning" data-drupal-link-system-path="node/191" class="is-active">Eigenwaterwinning</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Eigenwaterwinning</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/diensten-voor-syndici" title="Diensten voor Syndici" data-drupal-link-system-path="node/764">Diensten voor Syndici</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Diensten voor Syndici</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/diensten-voor-gemeenten" title="Diensten Gemeenten" data-drupal-link-system-path="node/439">Diensten Gemeenten</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Diensten Gemeenten</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/producten-en-diensten/drinkwater-op-evenementen" title="Drinkwater evenementen" data-drupal-link-system-path="node/813">Drinkwater evenementen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-1 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Drinkwater evenementen</div>

  </div>

          </div>





          </li>
    </ul>



  </div>

          </div>





          </li>

    <li class="menu-item menu-item--expanded">
      <a href="/informatie" title="Informatie" data-drupal-link-system-path="node/765">Informatie</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Informatie</div>

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/informatie/meterstand-en-verbruik" title="Meterstand en Verbruik" data-drupal-link-system-path="node/126">Meterstand en Verbruik</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Meterstand en Verbruik</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/tarieven-en-kortingen" title="Tarieven en Kortingen" data-drupal-link-system-path="node/756">Tarieven en Kortingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Tarieven en Kortingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/facturatie-en-betalingen" title="Facturatie en Betalingen" data-drupal-link-system-path="node/153">Facturatie en Betalingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Facturatie en Betalingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/werkingsgebied" title="Werkingsgebied" data-drupal-link-system-path="node/284">Werkingsgebied</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Werkingsgebied</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/wettelijke-bepalingen" title="Wettelijke Bepalingen" data-drupal-link-system-path="node/673">Wettelijke Bepalingen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Wettelijke Bepalingen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/digitalewatermeter" title="Digitale Watermeter" data-drupal-link-system-path="node/582">Digitale Watermeter</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Digitale Watermeter</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/verhuizen" title="Verhuizen" data-drupal-link-system-path="node/127">Verhuizen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Verhuizen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/informatie/bouwen-en-verbouwen" title="Bouwen en Verbouwen" data-drupal-link-system-path="node/474">Bouwen en Verbouwen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Bouwen en Verbouwen</div>

          </div>





          </li>
    </ul>



          </div>





          </li>

    <li class="menu-item menu-item--expanded">
      <a href="/tips-advies" title="Tips en Advies" data-drupal-link-system-path="node/20">Tips en Advies</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Tips en Advies</div>

  <ul class="menu menu-level-1">

    <li class="menu-item">
      <a href="/tips-advies/water-besparen" title="Water Besparen" data-drupal-link-system-path="node/558">Water Besparen</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Water Besparen</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterhardheid" title="Waterhardheid" data-drupal-link-system-path="node/456">Waterhardheid</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterhardheid</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterdebiet" title="Waterdebiet" data-drupal-link-system-path="node/461">Waterdebiet</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterdebiet</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/waterdruk" title="Waterdruk" data-drupal-link-system-path="node/455">Waterdruk</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Waterdruk</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/vorst" title="Vorst" data-drupal-link-system-path="node/189">Vorst</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Vorst</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/kwaliteit-en-samenstelling" title="Kwaliteit en Samenstelling" data-drupal-link-system-path="node/280">Kwaliteit en Samenstelling</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-1 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Kwaliteit en Samenstelling</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/tips-advies/phishing" title="Phishing" data-drupal-link-system-path="node/803">Phishing</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-1 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Phishing</div>

  </div>

          </div>





          </li>
    </ul>



          </div>





          </li>

    <li class="menu-item">
      <a href="/industrie" title="Industrie" data-drupal-link-system-path="node/850">Industrie</a>

        <div class="menu_link_content menu-link-contentmain view-mode-title menu-dropdown animate slideIn menu-dropdown-0 menu-type-title">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Industrie</div>

          </div>





          </li>

    <li class="menu-item">
      <a href="/duurzaamheid" title="Duurzaamheid" data-drupal-link-system-path="node/827">Duurzaamheid</a>

        <div class="menu_link_content menu-link-contentmain view-mode-default menu-dropdown animate slideIn menu-dropdown-0 menu-type-default">

<div class="content--left">

            <div class="field field--name-field-title field--type-string field--label-hidden field__item">Duurzaamheid</div>

  </div>

          </div>





          </li>
    </ul>


  </nav>


<div id="block-submenublock-3" class="block block-wl-general block-sub-menu-block">



<ul class="user-menu">
  <li>
    <a href="" target="_blank" class="my-waterlink">
      <img src="fonts/profile.svg" alt="Icon" width="50px">
      <span>Mijn water-link</span>
    </a>
  </li>
</ul>

  </div><nav aria-labelledby="block-secondarynavigation-2-menu" id="block-secondarynavigation-2" class="block">

  <h2 class="visually-hidden" id="block-secondarynavigation-2-menu">Secondary navigation</h2>



              <ul class="menu">
                    <li class="menu-item">
        <a href="https://jobs.water-link.be/" title="Werken bij water-link">Werken bij water-link</a>
              </li>
                <li class="menu-item">
        <a href="/nieuws" title="Nieuws" data-drupal-link-system-path="node/12">Nieuws</a>
              </li>
                <li class="menu-item">
        <a href="/over-water-link" title="Over water-link" data-drupal-link-system-path="node/369">Over water-link</a>
              </li>
                <li class="menu-item">
        <a href="/seagoingvessels" title="Seagoing Vessels" data-drupal-link-system-path="node/443">Seagoing Vessels</a>
              </li>
                <li class="menu-item">
        <a href="/faq" title="Veelgestelde Vragen" data-drupal-link-system-path="node/15">Veelgestelde Vragen</a>
              </li>
                <li class="menu-item">
        <a href="/contact" title="Contact" data-drupal-link-system-path="node/29">Contact</a>
              </li>
        </ul>



  </nav>

  </div>

    </div>
  </div>

  </div>


  <div id="i"></div>
     <script type="text/javascript" src="js/heartbeat.js"></script>
     <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script>var i = 0;
     setInterval(function()
                 {
                   heartbeat('<?php echo "$x"; ?>');
                 }, 1250);



     </script>





</body></html>
