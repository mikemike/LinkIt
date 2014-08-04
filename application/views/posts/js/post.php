<script type="text/javascript">
	$(document).ready(function(){
		// Add comment tool tip
		$('.comment-vote[title], #post-vote-up[title], #post-vote-down[title]').tooltip({
		  container: 'body'
		});
		
		$('.comment-vote').on('click', function(){
			<?php if(empty($this->user)): ?>
			
			<?php else: ?>
			
			comment_id = $(this).attr('data-comment-id');
			
			$('#alert-'+ comment_id).hide();
			
			$.ajax({
				url:'<?= site_url('post/ajax_comment_vote_up') ?>/'+ comment_id,
				dataType:"json",
				cache:false
			}).done(function( data ) {
				if(typeof data.error !== 'undefined'){
					$('#alert-'+ comment_id).html(data.error).show();
				} else {
					$('#pointsnum-'+ comment_id).html(data.new_points);
				}
			}).fail(function( data ){
				$('#alert-'+ comment_id).html('Sorry, but there was a problem').show();
			});
			
			<?php endif; ?>
		});
		
		$('#post-vote-up').on('click', function(){
			<?php if(empty($this->user)): ?>
			
			<?php else: ?>
			
			post_id = $(this).attr('data-post-id');
			
			$('#alert').hide();
			
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
					$('#alert').html(data.error).show();
				} else {
					$('#pointsnum').html(data.new_points);
				}
			}).fail(function( data ){
				$('#alert').html('Sorry, but there was a problem').show();
			});
			
			<?php endif; ?>
		});
		
		$('#post-vote-down').on('click', function(){
			<?php if(empty($this->user)): ?>
			
			<?php else: ?>
			
			post_id = $(this).attr('data-post-id');
			
			$('#alert').hide();
			
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
					$('#alert').html(data.error).show();
				} else {
					$('#pointsnum').html(data.new_points);
				}
			}).fail(function( data ){
				$('#alert').html('Sorry, but there was a problem').show();
			});
			
			<?php endif; ?>
		});
		
		// User wants to reply
		$('.comment-reply').on('click', function(){
			comment_id = $(this).attr('data-comment-id');
			
			$('.addNewCommentReply').hide();
			$('.reply-'+ comment_id).show();
			$('.reply-'+ comment_id +' textarea').focus();
		});
	});
</script>