
				<h1>Resend Email Confirmation</h1>
				
                <p>
                	Not received your activation email yet?  No problem, just send another.
                    Enter your email address below and we'll send the email straight away.
                    Be sure to check your spam folder, just in case.
                </p>
                
                <?php echo validation_errors(); ?>
				<?php echo form_open(current_url()); ?>  	
					<fieldset>
						<div class="control-group">
	                        <label class="control-label" for="email_address">Email Address</label>
    	                    <div class="controls">
        	    	            <input required="required" type="email" id="email_address" name="email" value="<?php echo set_value('email');?>">
            	            </div>
                        </div>
                        
                        <div class="control-group">
    	                    <div class="controls">	                        	
        	    	            <button type="submit" class="btn btn-success">Re-Send</button>
            	            </div>
                        </div>
					</fieldset>
					
				<?php echo form_close();?>