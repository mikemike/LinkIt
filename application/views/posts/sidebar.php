    <div class="mini-profile">
        <div class="image pull-left">
            <img src="<?= $this->gravatar->get_gravatar($post->email, null, 35); ?>" alt="" class="img-polaroid img-rounded">
        </div> <!-- .image -->
    
    	<div class="meta">
			<?= $post->first_name.' '.$post->last_name ?><br>
            <b><?= $post->member_points ?></b> points
        </div>
        
        <div class="muted">
	        Member since <?= date('M Y', strtotime($post->member_since)) ?>.<br>
            <b><?= $post->post_count ?></b> posts<br>
            <b><?= $post->comment_count ?></b> comments
        </div> <!-- .muted -->
                
        <?php if(!empty($post->twitter) || !empty($post->website)): ?>
        <p class="muted">
            <?php if(!empty($post->twitter)): ?>
                <br><a href="https://twitter.com/<?=$post->twitter?>" class="twitter-follow-button" data-show-count="false">Follow @<?=$post->twitter?></a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <?php endif; ?>

            <?= anchor('u/'.$post->username, $post->username."'s profile") ?><br>
            <?php if(!empty($post->website)): ?>
                <?= anchor($post->website, $post->username."'s website", 'target="_blank"') ?>
            <?php endif; ?>
        </p>
        <?php endif; ?>
        
        <hr>
            
    </div> <!-- .mini-profile -->
    
    <?php if(WEBSITE_ID == 1): ?>
    <div data-spy="affix" class="desktop-ad" >
        <script type="text/javascript"><!--
		google_ad_client = "ca-pub-6764806043460779";
		/* linkit.io | right */
		google_ad_slot = "8355651381";
		google_ad_width = 120;
		google_ad_height = 600;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
    </div>
        
    <div class="mobile-ad">
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-6764806043460779";
        /* linkit.io | small mob */
        google_ad_slot = "1611113786";
        google_ad_width = 234;
        google_ad_height = 60;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
    </div>
    <?php endif; ?>