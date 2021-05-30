<link href="<?php echo _Root ?>static/css/datatables.css" rel="stylesheet">
<script src="<?php echo _Root ?>static/js/datatables.js"></script>

<script src="<?php echo _Root ?>static/js/chart.js"></script>
<script src="<?php echo _Root ?>static/js/chart.utils.js"></script>


<div class="card m-4">
<div class="card-header" id="Requests"> تنظیمات سرور</div>
    <div class="card-body">
<?php
$date = new DateTime();
$timeZone = $date->getTimezone();
echo $timeZone->getName();

echo " (";

// $tz=timezone_open("Asia/Tehran");
// $dateTimeOslo=date_create("now",timezone_open("Europe/London"));
// echo timezone_offset_get($tz,$dateTimeOslo);

echo date('P');

echo ")";
?>
  </div>
</div>

<div class="card m-4" id="Posts">
    <div class="card-header">مهمانان امروز</div>
    <div class="card-body">
    <table class="table table-striped table-dark" style="table-layout: fixed;">
      <thead>
        <tr>
          <th scope="col">شناسه</th>
          <th scope="col">درخواست</th>
          <th scope="col" colspan="5">ایجنت</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($Data['TodayTopUsers'] as $item)
        {
        ?>
        <tr>
          <td><a class="btn btn-light btn-sm" href="<?php echo _Root . 'Admin/Statistics/' . $item['CLIENT_TRACK'] ?>"><?php echo substr($item['CLIENT_TRACK'], 0, 5) ?></a></td>
          <td><?php echo $item['TotalRequests'] ?></td>
          <td colspan="5" class="font-small"><?php echo $item['HTTP_USER_AGENT'] ?></td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    </div>
</div>

<div class="card m-4">
<div class="card-header" id="Requests">بازدید کننده‌های برتر هفت روز گذشته</div>
    <div class="card-body">
<?php
  foreach ($Data['WeekTopUsers'] as $item) {
    echo '<a class="btn bg-primary text-white d-block m-1" title="' . $item['HTTP_USER_AGENT'] . '"
    href="' . _Root . 'Admin/Statistics/' . $item['CLIENT_TRACK'] . '">'
    . $item['CLIENT_TRACK'] . ' (' . $item['TotalRequests'] . ')' . '</a>';
  }
?>
  </div>
</div>

<div class="card m-4">
    <div class="card-header" id="Requests">بازدید امروز</div>
    <div class="card-body">
        <canvas id="myAreaChart1" width="736" height="294" style="display: block; width: 736px; height: 294px;" class="chartjs-render-monitor"></canvas>
    </div>
</div>

<div class="card m-4">
    <div class="card-header" id="Requests">بازدید ماه‌های اخیر</div>
    <div class="card-body">
        <canvas id="myAreaChart" width="736" height="294" style="display: block; width: 736px; height: 294px;" class="chartjs-render-monitor"></canvas>
    </div>
</div>

<div class="card m-4" id="Agents">
    <div class="card-header">مرورگر‌ها</div>
    <div class="card-body">
        <canvas id="myPieChart" width="736" height="294" style="display: block; width: 736px; height: 294px;" class="chartjs-render-monitor"></canvas>
    </div>
</div>

<div class="card m-4" id="Posts">
    <div class="card-header">پربازدید ترین پست‌های نود روز گذشته</div>
    <div class="card-body">
    <table class="table table-striped table-dark" style="table-layout: fixed;">
      <thead>
        <tr>
          <th scope="col">تعداد درخواست</th>
          <th scope="col">مشاهده</th>
          <th scope="col" colspan="5">عنوان</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($Data['PostsVisitCountByAddress'] as $item)
        {
        ?>
        <tr>
          <td><?php echo $item['TotalVisits'] ?></td>
          <td><a class="btn btn-light btn-sm" href="<?php echo _Root . 'Home/View/' . $item['Id'] ?>">مشاهده</a></td>
          <td colspan="5" class="font-small"><?php echo $item['Title'] ?></td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    </div>
</div>

<script>
// ==== Area Chart 1 ====

var myLineChart = new Chart('myAreaChart1', {
  type: 'line',
  data: {
    labels: [
      <?php
      foreach ($Data['DailyGroupedVisitCountRows'] as $item)
      {
        echo '"' . $item['HourNumber'] . '", ';
      }
      ?>
      ],
    datasets: [{
      label: "بازدید‌ها",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: [
      <?php
      $max = 0;
      foreach ($Data['DailyGroupedVisitCountRows'] as $item)
      {
        $max = $max > $item['TotalVisits'] ? $max : $item['TotalVisits'];
        echo $item['TotalVisits'] . ', ';
      }
      ?>
      ],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: <?php echo $max + floor($max / 10) ?>,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});




// ==== Area Chart ====

var myLineChart = new Chart('myAreaChart', {
  type: 'line',
  data: {
    labels: [
      <?php
      foreach ($Data['GroupedVisitCountRows'] as $item)
      {
        echo '"' . $item['WeekNumber'] . '", ';
      }
      ?>
      ],
    datasets: [{
      label: "درخواست‌ها",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: [
      <?php
      $max = 0;
      foreach ($Data['GroupedVisitCountRows'] as $item)
      {
        $max = $max > $item['TotalVisits'] ? $max : $item['TotalVisits'];
        echo $item['TotalVisits'] . ', ';
      }
      ?>
      ],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: <?php echo $max + floor($max / 10) ?>,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});


// ==== Pie Chart ====
function randomColor() {
  return '#'+Math.floor(Math.random()*16777215).toString(16);
}

var data = {
  datasets: [{
    data: [
            <?php
            foreach ($Data['GroupedVisitCountByAgent'] as $item)
            {
              echo '"' . $item['TotalVisits'] . '", ';
            }
            ?>
					],
					backgroundColor: [
						randomColor(),
						randomColor(),
						randomColor(),
						randomColor(),
						randomColor(),
					],
					label: 'انواع مرورگر‌ها'
				}],
				labels: [
          <?php
          foreach ($Data['GroupedVisitCountByAgent'] as $item)
          {
            echo '"' . $item['Agent'] . '", ';
          }
          ?>
				]
  };

var options = {
  responsive: true
};

var chart = new Chart('myPieChart', {
  type: 'pie',
  data: data,
  options: options
});

chart.options.cutoutPercentage = 50;
chart.update();

</script>

        </div>
    </div>
</div>

<script type="text/javascript">
    function ToggleSidebar(){
        // Show and hide sidebar
        $('#sidebar').toggleClass('active');
    };
    $(document).ready(function(){
        // Show hints on hover
        $('[data-toggle="tooltip"]').tooltip();
        // Alow drag 'n drop
        $('[type="file"].ezdz').ezdz(); 
        // Standard tables
        $('table').DataTable();
        // Beautify inputs
        $('input, select').addClass("form-control");
    });
</script>