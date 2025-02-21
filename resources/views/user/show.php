<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($title); ?></title>
</head>
<body>
<h1>User Details</h1>

<div>
    <p>ID: <?php echo htmlspecialchars($user['id']); ?></p>
    <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Password: <?php echo htmlspecialchars($user['password']); ?></p>
</div>

</body>
</html>
