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

    if (array_key_exists("signup-submit", $_POST))
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

        if ($_POST['password'] != $_POST['confirmPassword'])
        {
            $error .= "Your password does not match. Try again.<br>";
        }

        else
        {
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0)
                {
                    $error = "That email address is taken.";
                }

                else
                {
                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query))
                    {
                        $error = "<p>Could not sign you up - please try again later.</p>";
                    }

                    else
                    {
                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        $id = mysqli_insert_id($link);

                        mysqli_query($link, $query);

                        $_SESSION['id'] = $id;

                        if ($_POST['stayLoggedIn'] == '1')
                        {
                            setcookie("id", $id, time() + 60*60*24*365);
                        }

                        echo("<script>location.href = 'loggedinpage.php?';</script>");

                    }

                }

              }

            }

?>

<?php include("header.php"); ?>

<div class="container" id="homePageContainer">

    <h1>Secret Diary</h1>

          <p><strong>Keep your thoughts securley in the Secret Diary. <br>If you are a new user create an account here.</strong></p>

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

    <fieldset class="form-group">
        <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password">
    </fieldset>

    <div class="checkbox">
        <label>
        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
        </label>
    </div>

    <fieldset class="form-group">
        <input class="btn btn-success" type="submit" name="signup-submit" value="Sign Up!">
    </fieldset>

    <p>Or<a href="index.php"> log in here.</a></p>

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
