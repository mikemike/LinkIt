<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
    <style>
		*,html,body,p{
			font-family:Helvetica, Arial;
			font-size:13px;	
		}
		p{
			font-family:Arial;	
		}
	</style>
</head>

<body>
	
    <p>
    	Thank you for signing up to <?= SITE_NAME ?>.  Before you can continue we need
        you to confirm your email address.  Please click the link below:<br />
        <?= anchor('login/confirm_email/'.$email_token) ?> 
    </p>
    <p>
    	Kind Regards,<br />
        <?= SITE_NAME ?>
    </p>

</body>
</html>