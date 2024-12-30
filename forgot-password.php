<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark" >

<div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Online Banking System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active text-white" aria-current="page" href="dashboarduser.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="">About_us </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Contact_us </a>
            </li>
            
        </ul>

        
    </div>
</div>
</nav>
<div class="LoginForm" style="width: 70vw; height: 60vh;  top: 10vh; position: absolute; padding: 1.5rem; box-sizing: border-box; border-radius: 10px; display: flex; flex-direction: column; ">

    <h1> Forgot Password(Enter you email address) </h1><br>
    
    <form method="post" action="send_password_reset.php">
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email">

        <button class="btn btn-primary">Send</button><br><br>
        <a href="loginpage.html">Return to login page</a>
    </form>
</div>

</body>
</html>