<?php
$spp_acct_download_file_option_btn = 
  <<<EOD
    <div class="director__features-container">
      <button class="button" id="statBtnEmbed">Embed</button>
      <button class="button" id="statBtnPHP">PHP</button>
      <button class="button" id="statBtnHTML">HTML</button>
    </div>
    <p id="embedInfo"></p>
     {{get_download_btn}}
    <div id="embedContainer">
      <code>
        &lt;div class="spp-container"&gt&lt;/div&gt
        <br>
        &lt;script src="{{google_jquery_link}}">&lt;/script&gt;
        <br>
        &lt;script src="{{js_link}}"&gt;&lt;/script&gt;
         <br>
        &lt;link rel="stylesheet" id="spp-css" href="{{style_link}}" type="text/css" media="all" /&gt;
         <br>
        &lt;script&gt;
         <br>
          &nbsp;$( document ).ready(function(){
        <br>
            &nbsp;&nbsp;var url = '{{target_url}}';
        <br>
            &nbsp;&nbsp;var request_url = document.URL;
        <br>
            &nbsp;&nbsp;var api_keys = '{{api_keys}}';
        <br>
            &nbsp;&nbsp;var secret_keys = '{{secret_keys}}';
        <br>
            &nbsp;&nbsp;var get_url = new URL(url);
        <br>
            &nbsp;&nbsp;get_url.searchParams.append('api_keys', api_keys);
        <br>
            &nbsp;&nbsp;get_url.searchParams.append('secret_keys', secret_keys);
        <br>
            &nbsp;&nbsp;get_url.searchParams.append('request_url', request_url);
        <br>
            &nbsp;&nbsp;$.ajax({
        <br>
              &nbsp;&nbsp;&nbsp;type: 'GET',
        <br>
              &nbsp;&nbsp;&nbsp;url: get_url.href,
        <br>
              &nbsp;&nbsp;&nbsp;success: function(response){
        <br>
                &nbsp;&nbsp;&nbsp;&nbsp;$('.spp-container').append(response);
        <br>
              &nbsp;&nbsp;&nbsp;}
        <br>
            &nbsp;&nbsp;})
        <br>
          &nbsp;});
        <br>
        &lt;/script&gt;
      </code>
    </div>
  EOD;
$spp_acct_slb_form = 
  <<<EOD
    <div>
      <h2>Features</h2>
      <p>As a Director you'll be able to add your Club Team's Stat Leaderboard to your website. </p>
       <p>We provide three different options (Embedding, PHP, HTML) for placing your the Stat Leaderboard onto your site!</p>
      <h3>Get Started</h3>
      <iframe width="560" height="315" src="https://www.youtube.com/embed/0iXiLh9Kv4k" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      <p class="small-margin-top">
      $spp_acct_download_file_option_btn
    </div>
  EOD;
$spp_acct_missing_owned_club_data_btn = 
  <<<EOD
    <div>
      <h2>Features</h2>
      <p>As a Director you'll be able to add your Club Team's Stat Leaderboard to your website. </p>
       <p>We provide three different options (Embedding, PHP, HTML) for placing your the Stat Leaderboard onto your site!</p>
      <h3>Get Started</h3>
      <iframe width="560" height="315" src="https://www.youtube.com/embed/0iXiLh9Kv4k" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
     <p class="small-margin-top">
     <label>Missing club data. Please contact our customer success at customersuccess@grassroots365.com</label>
     <div class="director__features-container" style="opacity: 65%;text-decoration: line-through;">
      <button class="button" >Embed</button>
      <button class="button" >PHP</button>
      <button class="button" >HTML</button>
    </div>
    </div>
  EOD;
$spp_slb_by_org = 
  <<<EOD
    <div class="spp-container"></div>
    <script src="{{google_jquery_link}}"></script>
    <script src="{{js_link}}"></script>
    <link rel="stylesheet" id="spp-css" href="{{style_link}}" type="text/css" media="all" />
    <script>
     $( document ).ready(function(){
      var url = '{{target_url}}';
      var request_url = document.URL;
      var api_keys = '{{api_keys}}';
      var secret_keys = '{{secret_keys}}';
      var request_data = '{{request_data}}';
      var get_url = new URL(url);
      get_url.searchParams.append('api_keys', api_keys);
      get_url.searchParams.append('secret_keys', secret_keys);
      get_url.searchParams.append('request_url', request_url);
      get_url.searchParams.append('request_data', request_data);
      $.ajax({
       type: 'GET',
       url: get_url.href,
       success: function(response){
        $('.spp-container').append(response);
       }
      })
     });
    </script>
  EOD;