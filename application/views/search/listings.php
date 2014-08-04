
<?php if(empty($posts)): ?>
	<p>
    	<h1>Oh no</h1>
        <p>
        	It looks like we don't have any results that match your search.
        </p>
        <p>
        	Really sorry about that, why not head to the home page and 
            read the <?= anchor('', 'latest posts') ?> instead?
        </p>
    </p>
<?php else: ?>
<div id="posts"> 
	<?php foreach($posts as $post): ?>

    <div class="single-post">
        <div class="vote pull-left">
            <a href="#" class="post-vote-up" data-post-id="<?= $post->post_id ?>" title="Vote up<?= (empty($this->user)?', you need to be logged in to do that' : '') ?>" data-toggle="tooltip" data-placement="right"><i class="icon-chevron-up"></i></a><br>
            <span class="points" id="pointsnum-<?= $post->post_id ?>"><?= $post->points ?></span><br>
            <a href="#" class="post-vote-down" data-post-id="<?= $post->post_id ?>" title="Vote down<?= (empty($this->user)?', you need to be logged in to do that' : '') ?>" data-toggle="tooltip" data-placement="right"><i class="icon-chevron-down"></i></a>
        </div> <!-- .vote -->
        
        <div class="discuss pull-right">
        	<a href="<?= site_url('l/'. url_title($post->title, '-', true) .'/'.$post->post_id); ?>" class="btn btn-info">Discuss</a><br>
            <a href="<?= site_url('l/'. url_title($post->title, '-', true) .'/'.$post->post_id); ?>" class="dlink"><?= $post->comment_count ?> comment<?=($post->comment_count==1?'':'s')?></a>
        </div> <!-- .discuss -->

        <div class="mainpost">
            <div class="image pull-left">
                <img src="<?= $this->gravatar->get_gravatar($post->email, null, 45); ?>" alt="" class="img-polaroid img-rounded">
            </div> <!-- .image -->
            <div class="meta">
                <h1><?= ($post->type == 'link' ? anchor($post->link, $post->title, 'target="_blank"') : anchor('l/'.url_title($post->title, '-', true).'/'.$post->post_id, '<i class="icon icon-comment" title="Discussion"></i> '.$post->title)) ?></h1>
                <p class="muted">
                	Posted on <?= date('g:ia jS F Y', strtotime($post->created)) ?> 
                    by <?= (!empty($post->external_name) ? '<b>'.$post->external_name.'</b>' : anchor('u/'.$post->username, $post->username))?>.
                </p>
            </div> <!-- .meta -->
        </div> <!-- .mainpost -->
        <p id="alert-<?= $post->post_id ?>" class="text-error hide"></p>
    </div> <!-- toppost -->        

	<hr>
	<?php endforeach; ?>
</div> <!-- #posts -->    

<div class="navigation">
    <?= $pages ?>
</div> <!-- .navigation -->
<?php endif; ?>