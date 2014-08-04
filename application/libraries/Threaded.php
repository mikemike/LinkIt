<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class threaded
{

    public $parents  = array();
    public $children = array();
	public $post;

    /**
     * @param array $comments
     */
    public function arrange($comments, $post)
    {
		$this->post = $post;
		
        foreach ($comments as $comment)
        {
            if ($comment->parent_comment_id === NULL)
            {
                $this->parents[$comment->post_comment_id][] = $comment;
            }
            else
            {
                $this->children[$comment->parent_comment_id][] = $comment;
            }
        }
        $this->print_comments();
    }

    private function tabulate($depth)
    {
        for ($depth; $depth > 0; $depth--)
        {
            echo "\t";
        }
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function format_comment($comment, $depth)
    {
		$CI =& get_instance();

        echo "\n";

        $this->tabulate($depth+1);
		// Margin for the comment nesting
		$margin = $depth*15;

        echo '<hr>
		<a name="'. $comment->post_comment_id .'"></a>
		<div class="comment" style="margin-left:'. $margin .'px">'."\n";
        echo $comment->comment;
        echo '
			<p>
				<span class="points">
					<a href="javascript:void(0);" class="comment-vote" data-comment-id="'.$comment->post_comment_id.'" title="Vote up'. (empty($CI->user)?', you need to be logged in to do that' : '') .'" data-toggle="tooltip" data-placement="bottom"><i class="icon-arrow-up"></i></a> <span id="pointsnum-'. $comment->post_comment_id .'">'. $comment->points .'</span> 
					<span id="alert-'. $comment->post_comment_id .'" class="text-error hide"></span>
				</span>
				<span class="muted">
					Posted on '. date('g:ia jS F Y', strtotime($comment->created)) .' by '.anchor('u/'.$comment->username, $comment->username, ($comment->user_id == $this->post->user_id ? 'class="author" title="Author of post"' : '')).'.
					';
					if(empty($CI->user)){
    					echo anchor('login', 'Login').' to reply.';
					} else {
						echo '<a href="javascript:void(0);" class="comment-reply" data-comment-id="'.$comment->post_comment_id.'">Reply</a>';
					}
					echo '
				</span>
		';
		if(!empty($CI->user)){
			echo '
				<div class="addNewComment addNewCommentReply hide reply-'. $comment->post_comment_id .'">
					<form action="'. site_url('add/comment/'.$this->post->post_id.'/'.$comment->post_comment_id) .'" method="post" class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="comment"><img src="'. $CI->gravatar->get_gravatar($CI->user['email'], null, 30) .'" alt="" class="img-polaroid img-rounded"></label>
							<div class="controls">
								<?= $error ?>
								<textarea type="text" id="comment" name="comment" placeholder="What do you have to say...?"></textarea>
							</div>
						</div>
						<button type="submit" class="btn btn-success btn-small pull-right">Add comment</button>
					</form>
				</div> <!-- .addNewComment -->
			';
		}
		echo '
			</p>
		</div>';
		
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function print_parent($comment, $depth = 0)
    {
        $this->tabulate($depth);
        
        foreach ($comment as $c)
        {
            $this->format_comment($c, $depth);

            if (isset($this->children[$c->post_comment_id]))
            {
                $this->print_parent($this->children[$c->post_comment_id], $depth + 1);
            }
        }
        $this->tabulate($depth);
        
    }

    private function print_comments()
    {
        foreach ($this->parents as $c)
        {
            $this->print_parent($c);
        }
    }

}