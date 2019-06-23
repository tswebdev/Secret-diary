<?php

    session_start();

    $error = "";

    if (array_key_exists("logout", $_GET))
    {
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";

        session_destroy();
    }

    else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id']))
    {
        header("Location: loggedinpage.php");
    }

                if (array_key_exists("login-submit", $_POST))
                {

                    include("connection.php");

                    if (!$_POST['email'])
                    {
                        $error .= "An email address is required<br>";
                    }

                    if (!$_POST['password'])
                    {
                        $error .= "A password is required<br>";
                    }

                    if ($error != "")
                    {
                        $error = "<p>There were error(s) in your form:</p>".$error;
                    }

                    else
                    {
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                    $result = mysqli_query($link, $query);

                    $row = mysqli_fetch_array($result);

                    if (isset($row))
                    {
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);

                        if ($hashedPassword == $row['password'])
                        {
                            $_SESSION['id'] = $row['id'];

                            if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1')
                            {
                                setcookie("id", $row['id'], time() + 60*60*24*365);
                            }

                            header("Location: loggedinpage.php");

                        }

                        else
                        {
                            $error = "That email/password combination could not be found.";
                        }

                    }

                    else
                    {
                        $error = "That email/password combination could not be found.";
                    }

                }

        }

?>

<?php include("header.php"); ?>

<div class="container" id="homePageContainer">

    <h1>Secret Diary</h1>

          <p><strong>Keep your thoughts securley in the Secret Diary. <br>Welcome back! Please log in!</strong></p>

          <div id="error">
            <?php
            if ($error!="")
            {
                echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
            }
            ?>
          </div>

<form method="post" id= "signUpForm">

    <fieldset class="form-group">
        <input class="form-control" type="email" name="email" placeholder="Your Email">
    </fieldset>

    <fieldset class="form-group">
        <input class="form-control" type="password" name="password" placeholder="Password">
    </fieldset>

    <div class="checkbox">
        <label>
        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
        </label>
    </div>

    <fieldset class="form-group">
        <input class="btn btn-success" type="submit" name="login-submit" value="Log in!">
    </fieldset>

    <p>Or <a href="creataccount.php">Create an account!</a></p>

</form>

      </div>

      <!-- jQuery first, then Bootstrap JS. -->
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>

           <script type="text/javascript">

               $('#diary').bind('input propertychange', function()
               {
                     $.ajax({
                       method: "POST",
                       url: "updatedatabase.php",
                       data: { content: $("#diary").val() }
                     });
              });


           </script>

       </body>
      </html>
