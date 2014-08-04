<h1>Your Details</h1>

<p>
	Use the form below to update your details.
</p>

<div class="row-fluid">
	<div class="span8">
        <form action="" method="post" class="form-horizontal signup">
            <?php echo validation_errors(); ?>
            
            <p>Want to change your username or email address?  <a href="mailto:<?= SITE_EMAIL ?>">Email us</a> with a full reason.</p>
            
            <div class="control-group">
                <label class="control-label" for="first_name">First Name</label>
                <div class="controls">
                    <input type="text" id="first_name" name="first_name" value="<?= set_value('first_name', $user->first_name) ?>">
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="last_name">Surname</label>
                <div class="controls">
                    <input type="text" id="last_name" name="last_name" value="<?= set_value('last_name', $user->last_name) ?>">
                </div>
            </div>
            
            <hr> 
            
            <div class="control-group">
                <label class="control-label" for="website">Website</label>
                <div class="controls">
                    <input type="text" id="website" name="website" value="<?= set_value('website', $user->website) ?>">
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="twitter">Twitter</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on">@</span>
                        <input id="twitter" name="twitter" type="text" value="<?= set_value('twitter', $user->twitter) ?>">
                    </div>
                </div>
            </div>
            
            <hr>
                                    
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input type="password" id="password" name="password">
                    <p class="help-block">Password must be more than 6 characters in length. Leave blank to not change.</p>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="confirm_password">Confirm Password</label>
                <div class="controls">
                    <input type="password" id="confirm_password" name="cpassword">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Update</button>
            </div>
        </form>
    </div> <!-- .span8 -->
    
    <div class="span4 text-center">
    	<img src="<?= $this->gravatar->get_gravatar($this->user['email'], null, 250); ?>" alt="" class="img-polaroid">
        <p>
        	Want to change this picture?  We use <?= anchor('www.gravatar.com', 'Gravatar') ?> - it's linked to your
            email address.
        </p>
    </div> <!-- .span4 -->
</div> <!-- .row-fluid -->