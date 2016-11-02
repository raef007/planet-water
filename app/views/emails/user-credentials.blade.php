<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
        <p><h3> Hello {{ $data_customer['company_name'] }} </p>
        <p> Please use this credentials to login to your Online Account at {{ URL::To('/') }}</p>
		<p>Username: {{ $data_user['email'] }}</p>
		<p>Password: {{ $password }}</p>
	</body>
</html>