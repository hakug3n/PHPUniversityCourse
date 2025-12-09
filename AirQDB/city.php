<?php
 require 'views/header.inc.php';
 $city = null;
 if(!empty($_GET['city'])){
    $city = $_GET['city'];
 }
 
    $filename = null;
 if(!empty($city)){
    $cities = json_decode(file_get_contents('C:\xampp\htdocs\php\AirQualityDataBrowser\data\index.json'), true);
    
    foreach ($cities as $c) {
        if($c['city'] === $city){
            $filename = $c['filename'];
            break;
        }
    }
 }
    $data = [];

 if(!empty($filename)){

    if (($handle = fopen('C:/xampp/htdocs/php/AirQualityDataBrowser/data/' . $filename, 'r')) !== false) {
        $headers = fgetcsv($handle, 1000, ',');

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($handle);
    }     

    $stats = [];

    foreach($data as $d){
        //var_dump($d);
         if($d['parameter'] !== 'pm25'){
             continue;
         }
         if($d['value'] <0){
             continue;
         }
        $date = substr($d['datetimeLocal'], 11 , 5);

        if(!isset($stats[$date])){
            $stats[$date] = [];
        }
        $stats[$date][] = $d['value'];
        //var_dump($stats)

    };    
    //         echo '<pre>';
    // var_dump($data[0]['datetimeLocal']);
    // echo '</pre>';
 }
    //  echo '<pre>';
    // echo 'Filename: ' . $filename . "\n";
    // echo 'City: ' . $city . "\n";
    // echo 'Data:' . "\n";
    // echo var_dump($data);
    // echo '</pre>';
 
?>
<div id="chartContainer" style="width: 80%; height: 400px; margin: auto;">
    <canvas id="myChart"></canvas>
</div>
<?php if (empty($city)): ?>
    <h1>No city selected</h1>
    <p>Please go back to the <a href="index.php">home page</a> and select a city.</p>
<?php else: ?>
    <h1>Air Quality Data for <?php echo $city; ?></h1>
    <p>Use the navigation menu to explore air quality data from various locations.</p>
<?php endif; ?>

<?php if (!empty($stats)): ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>PM2.5</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($stats as $date => $values): ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo round(array_sum($values) / count($values), 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>



<?php require 'views/footer.inc.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        {
                            type: 'line',
                            data: {
                                labels: [<?php foreach($stats as $date => $values): ?> '<?php echo $date; ?>', <?php endforeach; ?>],
                                datasets: [{
                                    label: 'PM2.5 Levels',
                                    data: [<?php foreach($stats as $date => $values): ?> <?php echo round(array_sum($values) / count($values), 2); ?>, <?php endforeach; ?>],
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'PM2.5 Concentration (µg/m³)'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Time of Day'
                                        }
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Average PM2.5 Levels by Time of Day'
                                    }
                                }
                            }
                        }
                    );
                </script>