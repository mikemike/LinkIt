<div id="post">
    
    <div class="toppost">
        <div class="vote pull-left">
            <a href="#" id="post-vote-up" data-post-id="<?= $post->post_id ?>" title="Vote up<?= (empty($this->user)?', you need to be logged in to do that' : '') ?>" data-toggle="tooltip" data-placement="right"><i class="icon-chevron-up"></i></a><br>
            <span class="points" id="pointsnum"><?= $post->points ?></span><br>
            <a href="#" id="post-vote-down" data-post-id="<?= $post->post_id ?>" title="Vote down<?= (empty($this->user)?', you need to be logged in to do that' : '') ?>" data-toggle="tooltip" data-placement="right"><i class="icon-chevron-down"></i></a>
        </div> <!-- .vote -->

        <div class="mainpost">
            <div class="image pull-left">
                <img src="<?= $this->gravatar->get_gravatar($post->email, null, 45); ?>" alt="" class="img-polaroid img-rounded">
            </div> <!-- .image -->
            <div class="meta">
                <h1><?= ($post->type == 'link' ? anchor($post->link, $post->title, 'target="_blank"') : $post->title) ?></h1>
                <p class="muted">Posted on <?= date('g:ia jS F Y', strtotime($post->created)) ?> by <?=(!empty($post->external_name) ? '<b>'.$post->external_name.'</b>' : anchor('u/'.$post->username, $post->username))?>.</p>
            </div> <!-- .meta -->
        </div> <!-- .mainpost -->
        <p id="alert" class="text-error hide"></p>
    </div> <!-- toppost -->
    
    <div class="comments">
        <?php if(!empty($comments)): ?>
            <?php $this->threaded->arrange($comments, $post) ?>
        <?php endif; ?>
    </div> <!-- .comments -->
    
    <hr>
    
    <?php if(empty($this->user)): ?>
    <p class="muted">
    	Please <?= anchor('login', 'login') ?> to discuss.
    </p>
    <?php else: ?>
    <div class="addNewComment">
    	<form action="<?= site_url('add/comment/'.$post->post_id) ?>" method="post" class="form-horizontal">
        	<div class="control-group">
                <label class="control-label" for="comment"><img src="<?= $this->gravatar->get_gravatar($this->user['email'], null, 30); ?>" alt="" class="img-polaroid img-rounded"></label>
                <div class="controls">
                    <?= $error ?>
                	<textarea type="text" id="comment" name="comment" placeholder="What do you have to say...?"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-small pull-right">Add comment</button>
        </form>
    </div> <!-- .addNewComment -->
    <?php endif; ?>
    
</div> <!-- .post -->