
				<h1>Register Account</h1>
							
                <?php echo validation_errors(); ?>
				<?php echo form_open(current_url()); ?>  	
					<fieldset>
						<legend>Personal Details</legend>
						<div class="control-group">
	                        <label class="control-label" for="first_name">First Name</label>
    	                    <div class="controls">
        	    	            <input required="required" type="text" id="first_name" name="first_name" value="<?php echo set_value('first_name');?>">
            	            </div>
                        </div>
						<div class="control-group">
	                        <label class="control-label" for="last_name">Last Name</label>
    	                    <div class="controls">
        	    	            <input required="required" type="text" id="last_name" name="last_name" value="<?php echo set_value('last_name');?>">
            	            </div>
                        </div>
                        
                        <hr>
                        
						<div class="control-group">
	                        <label class="control-label" for="email_address">Email Address</label>
    	                    <div class="controls">
        	    	            <input required="required" type="email" id="email_address" name="email" value="<?php echo set_value('email');?>">
            	            </div>
                        </div>
                        
						<div class="control-group">
	                        <label class="control-label" for="email_address">Username</label>
    	                    <div class="controls">
        	    	            <input required="required" type="text" id="username" name="username" value="<?php echo set_value('username');?>">
            	            </div>
                        </div>
                        
						<div class="control-group">
	                        <label class="control-label" for="password">Password</label>
    	                    <div class="controls">
        	    	            <input required="required" type="password" id="password" name="password">
                                <p class="help-block">Password must be more than 6 characters in length.</p>
            	            </div>
                        </div>
                        
						<div class="control-group">
	                        <label class="control-label" for="confirm_password">Confirm Password</label>
    	                    <div class="controls">
        	    	            <input required="required" type="password" id="confirm_password" name="cpassword">
            	            </div>
                        </div>
                        
                        <div class="control-group">
    	                    <div class="controls">	                        	
        	    	            <button type="submit" class="btn btn-success">Register</button>
            	            </div>
                        </div>
					</fieldset>
					
				<?php echo form_close();?>