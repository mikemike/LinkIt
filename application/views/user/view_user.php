<h1><?= $user->username ?></h1>
    
<div class="row-fluid">
    <div class="span9">
        <p>
            <?=$user->username?> has made a total of <strong><?= $num_posts ?> posts</strong>
            and <strong><?= $num_comments ?> comments</strong> since signing up on 
            <?= date('jS F, Y', strtotime($user->created)) ?>.
        </p>
        <p>
            <?=$user->username?> was last seen on 
            <?= date('jS F, Y', strtotime($user->last_login)) ?>.
        </p>

        <h2>Latest Posts</h2>
        <?php if(empty($latest_posts)): ?>
            <p><?=$user->username?> hasn't posted anything yet!</p>
        <?php else: ?>
            <ul>
                <?php foreach($latest_posts as $post): ?>
                <li><?= anchor('l/'.url_title($post->title, '-', true).'/'.$post->post_id, $post->title) ?>
                <?php endforeach; ?>
            </ul>
            <?= anchor('search/posts/1?u='.$user->username, 'see all posts by '.$user->username) ?>
        <?php endif; ?>
    </div> <!-- .span9 -->
    <div class="span3 text-center profile-avatar">
        <img src="<?= $this->gravatar->get_gravatar($user->email, null, 250); ?>" alt="" class="img-polaroid"><br>
        <p class="muted"><?= ucfirst($user->first_name) ?> <?= ucfirst($user->last_name) ?></p>
    </div> <!-- .span3 -->
</div> <!-- .row-fluid -->
