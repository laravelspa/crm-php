<?php 
include('main/header1.php');  
include('main/database.php');

$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 2 AND online = 1");
$stmt->execute();
$onlineSalesCount = $stmt->rowCount();
$onlineSales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 2 AND online = 0");
$stmt->execute();
$offlineSalesCount = $stmt->rowCount();
$offlineSales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 0 AND online = 1");
$stmt->execute();
$onlineAdminsCount = $stmt->rowCount();
$onlineAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 0 AND online = 0");
$stmt->execute();
$offlineAdminsCount = $stmt->rowCount();
$offlineAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $con->prepare("SELECT MONTH(created_at) as date, count(*) as count FROM pending GROUP BY MONTH(created_at)");
$stmt->execute();
$countPended = $stmt->rowCount();
$dataPending = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt = $con->prepare("SELECT MONTH(created_at) as date, count(*) as count FROM orderd GROUP BY MONTH(created_at)");
$stmt->execute();
$dataOrder = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt = $con->prepare("SELECT MONTH(canceld_at) as date, count(*) as count FROM canceld GROUP BY MONTH(canceld_at)");
$stmt->execute();
$dataCancel = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt = $con->prepare("SELECT * FROM project_count");
$stmt->execute();
$countProject = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pname = [];
$count = [];
foreach($countProject as $project) {
    $pname[] = $project['name'];
    $count[] = $project['count'];
}
$pn = implode($pname, ' ","');
$co = implode($count, ' ,');
?>


<!-- TABLE: LATEST ORDERS -->
<div class="card">
  <div class="card-header border-transparent">
    <h3 class="card-title">Latest actions</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <div class="table-responsive">
      <table class="table m-0">
        <thead>
        <tr>
          <th>Sales</th>
          <th>Status</th>
          <th>Comment</th>
          <th>Time on</th>
        </tr>
        </thead>
        <tbody class='actions_container'></tbody>
      </table>
    </div>
    <!-- /.table-responsive -->
  </div>
<!-- Loading (remove the following to stop the loading)-->
    <div class="overlay">
      <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <!-- end loading -->
  <!-- /.card-body -->
  <div class="card-footer clearfix">
    <a href="/all_actions.php" class="btn btn-sm btn-primary float-right">View All Actions</a>
  </div>
  <!-- /.card-footer -->
</div>
<!-- /.card -->

<!-- row -->
<div class="row">
    <div class="col-lg-6">
        <!-- Online sales -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Online sales</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-1">
            <?php if(count($onlineSales) > 0) { 
                foreach($onlineSales as $value) { ?>
                    <a href="track.php?emp_id=<?php echo $value['id']; ?>&name=<?php echo $value['name']; ?>" class="badge badge-info"><?php echo $value['name']; ?></a>
                <?php }
            } else { ?>
                <span class="badge badge-info">No Sales Online</span>
            <?php } ?>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-lg-6">
        <!-- Offline sales -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Offline sales</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-1">
            <?php if(count($offlineSales) > 0) { 
                foreach($offlineSales as $value) { ?>
                    <a href="track.php?emp_id=<?php echo $value['id']; ?>&name=<?php echo $value['name']; ?>" class="badge badge-info"><?php echo $value['name']; ?></a>
                <?php }
            } else { ?>
                <span class="badge badge-info">No Sales Offline</span>
            <?php } ?>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- / .row -->


<!-- row -->
<div class="row">
    <div class="col-lg-6">
        <!-- Online sales -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Online admins</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-1">
            <?php if(count($onlineAdmins) > 0) { 
                foreach($onlineAdmins as $value) { ?>
                    <span class="badge badge-dark"><?php echo $value['name']; ?></span>
                <?php }
            } else { ?>
                <span class="badge badge-dark">No Admins Online</span>
            <?php } ?>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <div class="col-lg-6">
        <!-- Offline sales -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Offline admins</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-1">
            <?php if(count($offlineAdmins) > 0) { 
                foreach($offlineAdmins as $value) { ?>
                    <span class="badge badge-dark"><?php echo $value['name']; ?></span>
                <?php }
            } else { ?>
                <span class="badge badge-dark">No Admins Offline</span>
            <?php } ?>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- / .row -->

<div class="row">
  <div class="col-lg-6">
   <!-- PIE CHART -->
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Products Count</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>

  <div class="col-lg-6">
     <!-- BAR CHART -->
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Orders status</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
  </div>

</div>
<div id="result"></div>
<?php include('main/footer1.php'); ?>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>

    <!-- <script src="/js/reconnect.min.js"></script> -->
    <script>
      $(document).ready(function() {
        // if(typeof(EventSource) !== "undefined") {
        //   // Yes! Server-sent events support!
        //   // Some code.....
        //   var source = new EventSource("demo_sse.php");
        //   source.onmessage = function(event) {
        //     var data = JSON.parse(event.data)
        //     console.log(data)
        //     document.getElementById("result").innerHTML += data.id + "<br>";
        //   };
        // } else {
        //   // Sorry! No server-sent events support..
        //   document.getElementById("result").innerHTML = "No Support<br>";
        // }

        // var conn = new WebSocket('ws://crm.healthy-cure.xyz:8000');
        // conn.onopen = function(e) {
        //     console.log("Connection established! 1");
        // };

        // conn.onmessage = function(e) {    
            
        // };
        // conn.onclose = function(e) {
        //   conn.onopen = function(e) {
        //     console.log("Connection established! 2");
        //   };
        // };


        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieDataJson  = {
          labels: [<?php echo '"'. $pn . '"' ?>],
          datasets: [
            {
              data: [<?php echo $co ?>],
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#00a65a', '#00c0ef', '#d2d6de']
            }
          ]
        }
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = pieDataJson;
        var pieOptions     = {
          maintainAspectRatio : false,
          responsive : true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var pieChart = new Chart(pieChartCanvas, {
          type: 'pie',
          data: pieData,
          options: pieOptions      
        })

        var areaChartData = {
          labels  : ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
          datasets: [
            {
              label: 'Pending',
              data: ['<?php echo $dataPending[1] ?>','<?php echo $dataPending[2] ?>','<?php echo $dataPending[3] ?>','<?php echo $dataPending[4] ?>','<?php echo $dataPending[5] ?>','<?php echo $dataPending[6] ?>','<?php echo $dataPending[7] ?>','<?php echo $dataPending[8] ?>','<?php echo $dataPending[9] ?>','<?php echo $dataPending[10] ?>','<?php echo $dataPending[11] ?>','<?php echo $dataPending[12] ?>'],
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)'
            },
            {
              label: 'Orderd',
              data: ['<?php echo $dataOrder[1] ?>','<?php echo $dataOrder[2] ?>','<?php echo $dataOrder[3] ?>','<?php echo $dataOrder[4] ?>','<?php echo $dataOrder[5] ?>','<?php echo $dataOrder[6] ?>','<?php echo $dataOrder[7] ?>','<?php echo $dataOrder[8] ?>','<?php echo $dataOrder[9] ?>','<?php echo $dataOrder[10] ?>','<?php echo $dataOrder[11] ?>','<?php echo $dataOrder[12] ?>'],
              backgroundColor     : 'rgba(40, 167, 69, .8)',
              borderColor         : 'rgba(40, 167, 69, .8)',
              pointRadius         : false,
              pointColor          : 'rgba(40, 167, 69, .8)',
              pointStrokeColor    : '#c1c7d1',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(40, 167, 69, .8)'
            },
            {
              label: 'Canceld',
              data: ['<?php echo $dataCancel[1] ?>','<?php echo $dataCancel[2] ?>','<?php echo $dataCancel[3] ?>','<?php echo $dataCancel[4] ?>','<?php echo $dataCancel[5] ?>','<?php echo $dataCancel[6] ?>','<?php echo $dataCancel[7] ?>','<?php echo $dataCancel[8] ?>','<?php echo $dataCancel[9] ?>','<?php echo $dataCancel[10] ?>','<?php echo $dataCancel[11] ?>','<?php echo $dataCancel[12] ?>'],
              backgroundColor     : 'rgba(186, 197, 189, .8)',
              borderColor         : 'rgba(186, 197, 189, .8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(186, 197, 189, .8)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(186, 197, 189, .8)'
            },
          ]
        }
        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        var temp2 = areaChartData.datasets[2]
        barChartData.datasets[0] = temp0
        barChartData.datasets[1] = temp1
        barChartData.datasets[2] = temp2

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }


        var barChart = new Chart(barChartCanvas, {
          type: 'bar', 
          data: barChartData,
          options: barChartOptions
        })
      })
      const actionsContainer = $('.actions_container');
      const overlay = $('.overlay');
      getActions()
      function getActions() {
        fetch('/latest_proc.php', {
              method: 'POST'
          }).then(res => {
              return res.json();
          }).then(response => {
              if(response.actions) {
                  overlay.css('display','none')
                  var html = '';
                  var classElement = '';
                  for(x in response.actions) {
                      if(response.actions[x].action === 'cancel') {
                          classElement = 'danger'
                      }

                      if(response.actions[x].action === 'approved' || response.actions[x].action === 'dod') {
                          classElement = 'success'
                      }
                      if(response.actions[x].action === 'call again' || response.actions[x].action === 'not answer') {
                          classElement = 'primary'
                      }
                      
                      html += `<tr>
                          <td>` + response.actions[x].name + `</td>
                          <td><span class="badge badge-pill badge-${classElement}">` + response.actions[x].action + `</span></td>
                          <td>` + response.actions[x].comment + `</td>
                          <td>` + response.actions[x].created_at + `</td>
                          </tr>
                      `;
                  }
                  actionsContainer.html(html);
              }
          })
      }
      setInterval(() => {
        getActions()  
      }, 25000);

    </script>
</body>
</html>