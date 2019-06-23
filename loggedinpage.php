<?php

  session_start();

  $diaryContent = "";

  if(array_key_exists("id", $_COOKIE) && $_COOKIE['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

  }

  if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {

    include("connection.php");

    $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

    $row = mysqli_fetch_array(mysqli_query($link, $query));

    $diaryContent = $row['diary'];

  } else {

    header("Location: index.php");
  }

  include("header.php");

?>
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
      <p><h5>Here you can save your thoughts.</h5></p>
      <div class="" id="navbarSupportedContent">
        <form class="form-inline my-2 my-lg-0">
          <a href="logout.php?logout=1"><button class="btn btn-success my-2 my-sm-0" type="button">Log out</button></a>
        </form>
      </div>
    </nav>

  <div class="container-fluid">

    <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>

  </div>


  <!-- jQuery first, then Bootstrap JS. -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>

       <script type="text/javascript">

           $('#diary').bind('input propertychange', function() {

             $.ajax({
                method: "POST",
                url: "updatedatabase.php",
                data: { content: $("#diary").val() }
                })

         });


       </script>

   </body>
  </html>
