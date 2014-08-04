<h1>User Login</h1>
	<div class="row-fluid">
    	<div class="span6">
			<?= validation_errors() ?>
            <?php if(!empty($error)): ?>
            	<div class="alert alert-error"><a data-dismiss="alert" class="close">&times;</a><strong>Error!</strong> <?= $error ?></div>
            <?php endif; ?>
				
				<?php echo form_open(current_url(), 'class="form-horizontal"');?>  	
					<fieldset >
						<legend>Registered Users</legend>
						
                        <div class="control-group">
	                        <label class="control-label" for="identity">Username</label>
    	                    <div class="controls">
        	    	            <input type="text" id="identity" name="username" value="<?php echo set_value('username');?>">
            	            </div>
                        </div>
						
                        <div class="control-group">
	                        <label class="control-label" for="password">Password</label>
    	                    <div class="controls">
        	    	            <input type="password" id="password" name="password" />
            	            </div>
                        </div>
						
                        <div class="control-group">
    	                    <div class="controls">
        	    	            <button type="submit" class="btn btn-primary">Login</button>
            	            </div>
                        </div>
						
                        <div class="control-group">
	                        <label class="control-label" for=""></label>
    	                    <div class="controls">
            	            </div>
                        </div>
						
                        <hr>
						
                        <a href="<?php echo base_url();?>login/forgotten_password">Forgotten your password or username?</a><br>
						<a href="<?php echo base_url();?>login/resend_email_confirmation">Resend account activation email</a>						
					</fieldset>
				</div> <!-- .span6 -->
                <div class="span6">
					<fieldset class="">
						<legend>Register</legend>
						<p>
                            New users can register for an account.  It's free.
                        </p>
                        <a href="<?php echo base_url();?>login/register" class="btn btn-success">Get Started</a>
					</fieldset>
				<?php echo form_close();?>
			</div> <!-- .span6 -->
        </div> <!-- .row-fluid -->