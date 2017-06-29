<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Nothing Here</title>


    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link type="text/css" rel="stylesheet" href="image.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <script type="text/javascript" src="javascrit.js"></script>
    <link rel="stylesheet" href="css/handsontable.bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.31.0/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.31.0/handsontable.full.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link type="text/css" rel="stylesheet" href="index.css">
    <script type="text/javascript" src="//www.google.com/jsapi"></script>
    <script type="text/javascript" src="index.js"></script>
    <script type="text/javascript" src="https://apis.google.com/js/client.js?onload=onJSClientLoad"></script>

    <script src="hot.js"></script>
    <script src="chart.js"></script>
    <!-- Load the AJAX API -->
    <script src="https://www.google.com/jsapi"></script>
    <script>
        // Load the Visualization API and the charts package.
        google.load('visualization', '1.0', {'packages':['table', 'corechart', 'charteditor']});
    </script>
    <script>
        $(function(){
            hot.init();
            chart.init();

            $('#chart-editor').click(chart.edit);
            $('#chart-generate').click(chart.embed);

            $('#clear').click(hot.clear);
            $('#add-col').click(hot.addColumn);
            $('#add-row').click(hot.addRow);
            $('#remove-col').click(hot.removeColumn);
            $('#remove-row').click(hot.removeRow);
            $('#add-row-10').click(function() {
                hot.addRows(10);
            });

            var data;
            if (data = getHashValue('d')) {
                data = JSON.parse(data);
                chart.load(data);
                hot.load(data);
            }

        });

        function getHashValue(key) {
            try {
                return location.hash.match(new RegExp(key + '=([^&]*)'))[1];
            } catch (e) {
                // not found key
            }
        }
    </script>
  </head>
  <body style='margin-top:0px;margin-left:100px;margin-right:100px;'>
    <link rel="stylesheet" type="text/css" href="style.css">
      <?php
      include "connect.php";
      if(isset($_FILES['files'])){
        $name = $_FILES['files']['name'];
        $size = $_FILES['files']['size'];
        $type = $_FILES['files']['type'];
        $tmp = $_FILES['files']['tmp_name'];
        $files = "uploads/".$_FILES['files']['name'];
        if(!file_exists($files)){

        $move = move_uploaded_file($tmp,$files);
        if($move){
          $add = $conn->prepare("insert into imageDB values('',?)");
          $add->bindparam(1,$name);
          if($add->execute()){
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> File upload successful.
            </div>
            <?php
          }else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Fail!</strong> File save to database failure.
            </div>
            <?php
          }
        }else {
          ?>
          <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Warning!</strong> The file has not been uploaded to the directory.
          </div>
          <?php
        }
      }else {
        ?>
        <div class="alert alert-info alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Warning!</strong> The file is already exist.
        </div>
        <?php
      }
    }
       ?>
       <div class="center-upload">
      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="files">Upload Files</label>
          <input type="file" id="files" name="files">
          <p class="help-block">All Files</p>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
      </form>
      </div>
      <style>
      .center-upload
      {

        margin: auto;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 100px;
      }
      </style>
    </br>
      <p></br></p>
      <div class="container">
        <ul class="nav nav-tabs" role="tablist" id="myTab">
          <li role="presentation" class="active"><a href="#image" aria-controls="image" data-toggle="tab"  >Image</a></li>
          <li role="presentation"><a href="#table" aria-controls="table"data-toggle="tab">Table and Chart</a></li>
          <li role="presentation"><a href="#youtube" aria-controls="youtube"data-toggle="tab">Youtube Report</a></li>
          <!-- <li role="presentation"><a href="#text" aria-controls="text"data-toggle="tab">Text</a></li> -->
        </ul>

        <div class="tab-content">
          <div  class="tab-pane fade in active" id="image">

            <?php
            $stmt = $conn->prepare("select * from imageDB");
            $stmt->execute();
            while ($row = $stmt->fetch()) {

             ?>
            <div class="responsive">
            <div class="col-lg-4 col-md-3">
              <div class="thumbnail" data-toggle="modal" data-target="#image-gallery">
                <img class="myImg"  style="height:200px; width:350px" src="uploads/<?php echo $row['image'] ?>" alt="<?php echo $row['image'] ?>" title="<?php echo $row['image'] ?>">
                <div class="caption text-center">
                  <p><a href="?delete=<?php echo $row['image'] ?>&id=<?php echo $row['id'] ?>" class="btn btn-primary" role="button">Delete</a></p>
                </div>
              </div>
            </div>
            </div>
            <?php
            }
            ?>
            <!-- The Modal -->
            <div id="myModal" class="modal">
              <!-- The Close Button -->
              <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
              <!-- Modal Content (The Image) -->
              <img class="modal-content" id="img01">
              <!-- Modal Caption (Image Text) -->
              <div id="caption"></div>
            </div>
            <script>
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = $('.myImg');
            var modalImg = $("#img01");
            var captionText = document.getElementById("caption");
            $('.myImg').click(function(){
                modal.style.display = "block";
                var newSrc = this.src;
                modalImg.attr('src', newSrc);
                captionText.innerHTML = this.alt;
            });

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
              modal.style.display = "none";
            }
            </script>

          </div>

          <div  class="tab-pane fade" id="table">
              <div class="container theme-showcase" role="main">
                <div class="row">
                    <div class="col-xs-12 col-md-8" role="main" >
                        <h3>Data:</h3>
                        <div id="hot"></div>
                        <div class="btn-group" role="group">
                            <button id="add-row" type="button" class="btn btn-default btn-sm" title="Add Row">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Row
                            </button>
                            <button id="add-row-10" type="button" class="btn btn-default btn-sm" title="Add 10 Rows">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                10
                            </button>
                            <button id="remove-row" type="button" class="btn btn-default btn-sm" title="Remove Row">
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                Row
                            </button>
                        </div>
                        <button id="add-col" type="button" class="btn btn-default btn-sm pull-right" title="Add Column">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            Col
                        </button>
                        <button id="remove-col" type="button" class="btn btn-default btn-sm pull-right" title="Add Column">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                            Col
                        </button>
                        <button id="clear" type="button" class="btn btn-danger btn-sm" title="Clean">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                        <br/>
                        <br/>
                    </div>
                </div>
                <div class="footer ">
                    <div class="btn-group" role="group">
                        <button id="chart-generate" type="button"  data-toggle="modal" class="btn btn-primary">Generate Chart</button>
                        <button id="chart-editor" type="button" class="btn btn-default">Edit Chart</button>
                    </div>
                </div>
              </br>
              <div class="modal fade" id="embed-modal" tabindex="-1" role="dialog" aria-labelledby="embedLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="embedLabel">Charts is ready!</h4>
                        </div>
                        <div class="modal-body">
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-lg-2"></div>
                              <div id="chart"></div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
              </div>
        </div>
        <!-- pull the data from youtube analytics -->
        <div  class="tab-pane fade" id="youtube">
          <!-- <form method="post">
            <h1>Please Enter Your Youtube Analytics Client ID</h1>
            <div class="input-group input-group-lg">
              <span class="input-group-addon">Youtube Client ID</span>
              <input id="clientIDd" name="clientID" type="text" class="form-control" aria-describedby="basic-addon1">
              <span class="input-group-btn">
                <button class="btn btn-primary"  type="submit" onclick="analytics()">Submit</button>
              </span>
            </div>
          </form> -->

          <div id="login-container" class="pre-auth">This application requires access to your YouTube account.
            Please <a href="#" id="login-link">authorize</a> to continue.
          </div>
          <div class="post-auth">
          </br>
            <h3> <div><span class="label label-default">Choose a Video:</span></div></h3>
            <ul id="video-list"></ul>
          </br>
            <div id="message"></div>
            <div id="chart-youtube"></div>
          </div>
        </div>
      </div>
      <!-- Keep the current tab when refreash -->
      <script>
      $('#myTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
      });

      // store the currently selected tab in the hash value
      $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
        var id = $(e.target).attr("href").substr(1);
        window.location.hash = id;
      });

      // on load of the page: switch to the currently selected tab
      var hash = window.location.hash;
      $('#myTab a[href="' + hash + '"]').tab('show');
      </script>

      <p><br /></p>


        <?php
        if(isset($_GET['delete'])){
          $img = $_GET['delete'];
          $id = $_GET['id'];
          $delete = unlink('uploads/'.$img);
          if($delete){
            $hps = $conn->prepare("DELETE from imageDB where id='$id'");

            if($hps->execute()){
              ?>
              <script>
                window.location.href = '/database/index.php';
                </script>
              <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Success!</strong> The Files have been deleted in directories and databases.
              </div>
              <?php

            }else {
              ?>
              <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Fail!</strong> The file failed to be deleted in the database.
              </div>
              <?php
            }
          }else {
            ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Error!</strong> The file has not been deleted to the directory.
            </div>
            <?php
          }

        }
        ?>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script> -->

  </body>
</html>
