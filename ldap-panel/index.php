<?php

	// ob_start();
	// session_start();

	// if (!session_is_registered(authuser)) {
	// 	header('Location: http://localhost/ldappanel/panel.php');	
	// };
	?>
<html>
<head>
        <link id="rem" rel="stylesheet" href="css/sample.css"/>
        <script src="js/styles.js"></script>
	<title>LDAP PANEL</title>
</head>
<body>

                   <form id="login" action="authenticator.php" method="post">
                    <h1>ورود مدیران</h1>
                        <fieldset id="inputs">
                            <input id="username" placeholder="Username" name="user" type="text">   
                            <input id="password" placeholder="Password" name="pass" type="password">
                        </fieldset>
                        <fieldset id="actions">
                            <input id="submit" value="ورود" type="submit">
                        </fieldset>
                 </form>

</body>
</html> 