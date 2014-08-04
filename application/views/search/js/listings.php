<script type="text/javascript" src="<?= site_url('assets/js/jquery.infinitescroll.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.post-vote-up[title], .post-vote-down[title]').tooltip({
			container: 'body'
		});

		$('#posts').infinitescroll({
			navSelector  : "div.navigation",            
			nextSelector : "div.navigation a:first",    
			itemSelector : "#posts",          
			debug        : false,                        		
			loadingText  : "Loading new posts...",      			
			animate      : true,      			
			extraScrollPx: 50,      			
			bufferPx     : 40,			
			errorCallback: function(){},                 
			
			localMode    : true
		},function(arrayOfNewElems){			
			 // optional callback when new content is successfully loaded in.
			
			 // keyword `this` will refer to the new DOM content that was just added.
			 // as of 1.5, `this` matches the element you called the plugin on (e.g. #content)
			 //                   all the new elements that were found are passed in as an array
		});
		
		
		$('.post-vote-up').on('click', function(){
			<?php if(empty($this->user)): ?>
			
			<?php else: ?>
			
			post_id = $(this).attr('data-post-id');
			
			$('#alert-'+post_id).hide();
			
			$.ajax({
				url:'<?= site_url('post/ajax_post_vote') ?>/'+ post_id,
				type:'POST',
				dataType:"json",
				cache:false,
				data: { 
					value:'1'
				}
			}).done(function( data ) {
				if(typeof data.error !== 'undefined'){
					$('#alert-'+post_id).html(data.error).show();
				} else {
					$('#pointsnum-'+post_id).html(data.new_points);
				}
			}).fail(function( data ){
				$('#alert-'+post_id).html('Sorry, but there was a problem').show();
			});
			
			<?php endif; ?>
		});
		
		$('.post-vote-down').on('click', function(){
			<?php if(empty($this->user)): ?>
			
			<?php else: ?>
			
			post_id = $(this).attr('data-post-id');
			
			$('#alert-'+post_id).hide();
			
			$.ajax({
				url:'<?= site_url('post/ajax_post_vote') ?>/'+ post_id,
				type:'POST',
				dataType:"json",
				cache:false,
				data: { 
					value:'-1'
				}
			}).done(function( data ) {
				if(typeof data.error !== 'undefined'){
					$('#alert-'+post_id).html(data.error).show();
				} else {
					$('#pointsnum-'+post_id).html(data.new_points);
				}
			}).fail(function( data ){
				$('#alert-'+post_id).html('Sorry, but there was a problem').show();
			});
			
			<?php endif; ?>
		});
	});
</script>