<div id="rrssb-buttons">
<div class="title">
	<p>Share</p>
</div>
<ul class="rrssb-buttons">
      <li class="rrssb-facebook">
        <!--  Replace with your URL. For best results, make sure you page has the proper FB Open Graph tags in header:
              https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content/ -->
        <a href="http://www.facebook.com/sharer.php?u=<?php echo $_SERVER['HTTP_HOST']?>/<?php echo $this->params->url;?>" class="popup" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29"><path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/></svg>
          </span>
          <span class="rrssb-text">Facebook</span>
        </a>
      </li>
       <li class="rrssb-twitter">
        <!-- Replace href with your Meta and URL information  -->
        <a href="https://twitter.com/intent/tweet?text=<?php echo $_SERVER['HTTP_HOST']?>/<?php echo $this->params->url;?>" class="twitter" class="popup" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/></svg>
          </span>
          <span class="rrssb-text">Twitter</span>
        </a>
      </li>
       <li class="rrssb-googleplus">
        <!-- Replace href with your meta and URL information.  -->
        <a href="https://plus.google.com/share?url=<?php echo $_SERVER['HTTP_HOST']?>/<?php echo $this->params->url;?>" class="popup" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"/></svg>            </span>
        </a>
      </li>
      <li class="rrssb-pinterest">
        <!-- Replace href with your meta and URL information.  -->
        <a href="http://pinterest.com/pin/create/button/?url=<?php echo $_SERVER['HTTP_HOST']?>/<?php echo $this->params->url;?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M14.02 1.57c-7.06 0-12.784 5.723-12.784 12.785S6.96 27.14 14.02 27.14c7.062 0 12.786-5.725 12.786-12.785 0-7.06-5.724-12.785-12.785-12.785zm1.24 17.085c-1.16-.09-1.648-.666-2.558-1.22-.5 2.627-1.113 5.146-2.925 6.46-.56-3.972.822-6.952 1.462-10.117-1.094-1.84.13-5.545 2.437-4.632 2.837 1.123-2.458 6.842 1.1 7.557 3.71.744 5.226-6.44 2.924-8.775-3.324-3.374-9.677-.077-8.896 4.754.19 1.178 1.408 1.538.49 3.168-2.13-.472-2.764-2.15-2.683-4.388.132-3.662 3.292-6.227 6.46-6.582 4.008-.448 7.772 1.474 8.29 5.24.58 4.254-1.815 8.864-6.1 8.532v.003z"/></svg>
          </span>
        </a>
      </li>
      <ul class="social2">
		<?php if($a_site_info['facebook_like']){?>
		<li class="facebook"><div class="fb-like" data-send="false" data-layout="button_count" data-width="110" data-show-faces="false" data-font="arial"></div></li>
		<?php }if($a_site_info['twitter_like']){?>
		<li class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a></li>
		<?php }if($a_site_info['google_like']){?>
		<li class="google"><div class="g-plusone" data-size="medium"></div></li>
		<?php }if($a_site_info['linkedin_like']){?>
		<li class="linkedin"><script type="IN/Share" data-counter="right"></script></li>
		<?php }?>
	</ul> <!--  end .social -->
</ul>
</div>