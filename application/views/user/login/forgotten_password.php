
				<h1>Forgotten Password</h1>
				
                <p>
                	Not sure of your password or username?  Pop in your email below and we'll reset it for you.
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
        	    	            <button type="submit" class="btn btn-success">Go</button>
            	            </div>
                        </div>
					</fieldset>
					
				<?php echo form_close();?>