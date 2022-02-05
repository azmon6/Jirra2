<head>
        <title>Jirra2</title>
        <link rel="stylesheet" href="main.css">
        <script type="text/javascript" src="testing.js"></script>
</head>

<body>
        <div>What?</div>
        <form method="post" action="#" onsubmit="">
                <input type="search" name="search" /><br />
                <input type="submit" name="submit" value="Search" /><br />
        </form>

        <input type="submit" name="testing" value="Testing" onclick="testing()">

        <div id="plach"></div>

        Welcome <?php if (isset($_POST["search"])) {echo $_POST["search"];} ?>
        Your email address is: <?php echo $_POST["submit"]; ?>
</body>