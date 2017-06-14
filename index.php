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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="javascrit.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style='margin-top:0;margin-left:100px;margin-right:100px;'>


    <p><br /><br /></p>
    <link rel="stylesheet" type="text/css" href="style.css">

    <div class="container">
      <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Link</a></li>

      </ul>


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

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
      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="files">Upload Files</label>
          <input type="file" id="files" name="files">
          <p class="help-block">All Files</p>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
      </form>
      <div class="container">
        <ul class="nav nav-tabs" role="tablist" id="myTab">
          <li role="presentation" class="active"><a href="#image" aria-controls="image" data-toggle="tab"  >Image</a></li>
          <li role="presentation"><a href="#chart" aria-controls="chart" data-toggle="tab">Chart</a></li>
          <li role="presentation"><a href="#table" aria-controls="table"data-toggle="tab">Table</a></li>
          <li role="presentation"><a href="#text" aria-controls="text"data-toggle="tab">Text</a></li>
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
              <div class="thumbnail">
                <img style="height:200px; width:350px" src="uploads/<?php echo $row['image'] ?>" alt="<?php echo $row['image'] ?>" title="<?php echo $row['image'] ?>">
                <div class="caption text-center">
                  <p><a href="?delete=<?php echo $row['image'] ?>&id=<?php echo $row['id'] ?>" class="btn btn-primary" role="button">Delete</a></p>
                </div>
              </div>
            </div>
            </div>
            <?php
            }
            ?>
          </div>


          <div  class="tab-pane fade" id="chart">
            <div id="dashboard_div">
              <!--Divs that will hold each control and chart-->
              <div id="filter_div"></div>
              <div id="chart_div"></div>
            </div>

          <script type="text/javascript">

            // Load the Visualization API and the controls package.
            google.charts.load('current', {'packages':['corechart', 'controls']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawDashboard);

            // Callback that creates and populates a data table,
            // instantiates a dashboard, a range slider and a pie chart,
            // passes in the data and draws it.
            function drawDashboard() {

              // Create our data table.
              var data = google.visualization.arrayToDataTable([
                ['Name', 'Donuts eaten'],
                ['Michael' , 5],
                ['Elisa', 7],
                ['Robert', 3],
                ['John', 2],
                ['Jessica', 6],
                ['Aaron', 1],
                ['Margareth', 8]
              ]);

              // Create a dashboard.
              var dashboard = new google.visualization.Dashboard(
                  document.getElementById('dashboard_div'));

              // Create a range slider, passing some options
              var donutRangeSlider = new google.visualization.ControlWrapper({
                'controlType': 'NumberRangeFilter',
                'containerId': 'filter_div',
                'options': {
                  'filterColumnLabel': 'Donuts eaten'
                }
              });



              // Create a pie chart, passing some options
              var pieChart = new google.visualization.ChartWrapper({
                'chartType': 'PieChart',
                'containerId': 'chart_div',
                'options': {
                  'width': 700,
                  'height': 700,
                  'pieSliceText': 'value',
                  'legend': 'right',
                  'is3D': 'true'
                }
              });


              // Establish dependencies, declaring that 'filter' drives 'pieChart',
              // so that the pie chart will only display entries that are let through
              // given the chosen slider range.
              dashboard.bind(donutRangeSlider, pieChart);

              // Draw the dashboard.
              dashboard.draw(data);
            }
          </script>
          </div>
          <div  class="tab-pane fade" id="table">
            <div class="jumbotron">

              <div class="container">
                <p>
                  <a class="btn btn-primary btn-lg" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Create Table
                  </a>
                  <button class="btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Show Table
                  </button>
                </p>
                <div class="collapse" id="collapseExample">
                  <div class="card card-block">
                    <?php
                    // Connect to the DB
                    include "connect.php";

                    // store in the DB
                    if(!empty($_POST['ok'])) {
                      // adding new table1
                      if(!empty($_POST['value'])) {
                        foreach($_POST['value'] as $cnt => $value) {
                          $sql = "INSERT INTO table1 (value, name) VALUES ('$value', '".$_POST['name'][$cnt]."');";
                          $conn->query($sql);
                        }
                      }
                    }

                    // select existing table1 here
                    $sql="SELECT * FROM table1 ORDER BY id";
                    $result = $conn->query($sql);
                    ?>
                    <form method="post">
                      <div id="itemRows">
                      <strong>Item Name</strong><span style="display:inline-block; width: 80px;"></span> <strong>Item Values</strong> <br/>
                      <p><input type="text" name="add_name" /> <input type="number" name="add_value" /> <input onclick="addRow(this.form);" type="button" value="Add row" /> <font size="3">(This row will not be saved unless you click on "Add row" first)</font></p>
                      </div>

                      <button type="submit" name="ok" >Save Changes</button>
                      </form>
                  </div>
                </div>


                <script type="text/javascript">
                var rowNum = 0;
                function addRow(frm) {
                  rowNum ++;
                  var row = '<p id="rowNum'+rowNum+'"> <input type="text" name="name[]" value="'+frm.add_name.value+'"> <input type="number" name="value[]" value="'+frm.add_value.value+'"> <input type="button" value="Remove" onclick="removeRow('+rowNum+');"></p>';
                  jQuery('#itemRows').append(row);
                  frm.add_value.value = '';
                  frm.add_name.value = '';
                }

                function removeRow(rnum) {
                  jQuery('#rowNum'+rnum).remove();
                }
                </script>
              </div>
            </div>
          </div>

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
        </div>
      </div>


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>


  </body>
</html>
