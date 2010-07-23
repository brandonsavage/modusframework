<html>
	<head>
		<title>
			An Error Has Occured
		</title>
	</head>
	<body>

Unfortunately there was an error with your request. The error details are:<p>

<em>
<?php

if($vars instanceof ModusException)
{
	echo $vars->getUserMessage();
}
else
{
	echo $vars;
}

?>

</em>

<p>
We regret this error and apologize for any inconvenience. Please contact the system administrator for help.

	</body>
</html>