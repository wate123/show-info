<?php
//if upload buttom is pressed
if (isset($_POST['upload'])) {
  //path to store the uploaded image
  $target = "image/".basename($_FILES['image']['name']);

  require_once('/databse/connect.php');
}
 ?>
<html>
<head>
  <title>Nothing Here</title>
  <style type="text/css">

  table{
    background-color:#FCF;
  }

  th{
    width:150px;
    text-align:left;
  }

  </style>
</head>
<body>
  <h1>Home page</h1>
  <!-- <form name="form" action="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td>Select File</td>
        <td><input type="file" name="f1" > <input type="submit" name="submit1" VALUES"upload" ></td>
      </tr>
    </table>
  </form> -->
  <div id="content">
      <form method="post" action="home.php" enctype="multipart/form-data">
        <input type="hidden" name="size" value="1000000">
        <div>
          <textarea name="text" cols="40" rows="4" placeholder="Decription of the file"></textarea>
        </div>
        <div>
          <input type="submit" name="upload" value="Upload">
        </div>
      </form>
  </div>


</body>
</html>
