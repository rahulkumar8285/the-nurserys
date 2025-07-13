<?php
/*******************************************************************************
*  Invoice Management System                                                *
*                                                                              *
* Version: 1.0	                                                               *
* Developer:  Abhishek Raj                                   				           *
*******************************************************************************/

include('header.php');
include('functions.php');
include_once("includes/config.php");

// 2. Fetch month-wise sales with year
$query = "
    SELECT 
        DATE_FORMAT(invoice_date, '%b %Y') AS month_year,
        SUM(CAST(invoice_total AS DECIMAL(10,2))) AS total_sales
    FROM invoices
    WHERE invoice_date IS NOT NULL AND invoice_total IS NOT NULL
    GROUP BY YEAR(invoice_date), MONTH(invoice_date)
    ORDER BY YEAR(invoice_date), MONTH(invoice_date);
";

$result = mysqli_query($mysqli, $query);

// 3. Process data into arrays
$months = [];
$sales = [];
$totalSales = 0;

while($row = $result->fetch_assoc()) {
    $months[] = $row['month_year'];
    $sales[] = (float)$row['total_sales'];
    $totalSales += (float)$row['total_sales'];
}
?>




  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
 
 
  <h2>Monthly Sales Chart</h2>
  <div id="totalSales">Total Sales: ₹<?= number_format($totalSales) ?></div>
  <canvas id="salesChart"></canvas>


   <script>
    const labels = <?= json_encode($months) ?>;
    const salesData = <?= json_encode($sales) ?>;

    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Monthly Sales (₹)',
          data: salesData,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.1)',
          tension: 0.4,
          fill: true,
          pointRadius: 5,
          pointHoverRadius: 8
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Sales Overview by Month and Year'
          },
          datalabels: {
            display: true,
            color: '#333',
            anchor: 'end',
            align: 'top',
            font: {
              weight: 'bold'
            },
            formatter: value => '₹' + value.toLocaleString()
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => '₹' + value
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>

  
  
  


<?php
	include('footer.php');
?>