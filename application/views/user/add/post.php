<h1>Add a Post</h1>

<div class="row-fluid">
	<div class="span12" id="formAdd">
        <form action="" method="post" class="form-horizontal signup">
            <?php echo validation_errors(); ?>
            
            <div class="control-group">
                <label class="control-label" for="type">What are you posting?</label>
                <div class="controls">        	    	    
                    <select name="type" class="chosen-add">
                        <option value="link" <?= set_select('type', 'link') ?>>Link</option>
                        <option value="discussion" <?= set_select('type', 'discussion') ?>>Discussion</option>
                    </select>
                </div>
            </div>
            
            <div id="addModalLink">    
                <div class="control-group">            	
                    <label class="control-label" for="link">Link</label>
                    <div class="controls">        	    	    
                        <input type="text" name="link" id="link" required="required" value="<?= set_value('link') ?>">
                        <div class="progress progress-striped active hide help-block">
                            <div class="bar" style="width: 100%;"></div>
                        </div>
                        <div class="help-block text-error" id="err"></div>
                        <div class="help-block" id="desc"></div>
                    </div>
                </div>
                    
                <div class="control-group">
                    <label class="control-label" for="link_title">Title</label>
                    <div class="controls">        	    	    
                        <input type="text" name="link_title" id="link_title" value="<?= set_value('link_title') ?>" required="required">
                    </div>
                </div>
            </div>
            
            <div id="addModalDiscussion" class="hide">                          
                <div class="control-group">
                    <label class="control-label" for="desc_title">Title</label>
                    <div class="controls">        	    	    
                        <input type="text" name="desc_title" id="desc_title" value="<?= set_value('desc_title') ?>">
                        <p class="help-block">Keep it descriptive and engaging</p>
                    </div>
                </div>
                                
                <div class="control-group">            	
                    <label class="control-label" for="comment">First comment</label>
                    <div class="controls">        	    	    
                        <textarea name="comment" id="comment" style="height: 130px; width: 320px;"><?= set_value('comment') ?></textarea>
                        <p class="help-block">Get the ball rolling</p>
                    </div>
                </div>
            </div>
        
            <div class="form-actions">
                <button type="submit" class="btn btn-primary addBtn" id="addModalBtn">Add Link</button>
            </div>
        </form>
    </div> <!-- .span12 -->
</div> <!-- .row-fluid -->