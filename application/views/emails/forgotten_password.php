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
    	Your password has been reset for <?= SITE_NAME ?>.
    </p>
    <p>
    	Username: <?= $emailuser->username ?><br />
        Password: <?= $pass ?>
    </p>
    <p>
    	You can log in here: <?= site_url('login') ?>
    </p>
    <p>
    	Kind Regards,<br />
        <?= SITE_NAME ?>
    </p>

</body>
</html>