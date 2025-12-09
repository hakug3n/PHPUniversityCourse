<?php


// $data = [];
// if (($handle = fopen('C:\xampp\htdocs\php\AirQualityDataBrowser\data\berlin.json.csv', 'r')) !== false) {
//     $headers = fgetcsv($handle, 1000, ',');

//     while (($row = fgetcsv($handle, 1000, ',')) !== false) {
//         $data[] = array_combine($headers, $row);
//     }
//     fclose($handle);
// }

// var_dump($data);
?>
<?php require   'views/header.inc.php'; ?>

    <h1>Air Quality Data Browser</h1>
    <p>Welcome to the Air Quality Data Browser. Use the navigation menu to explore air quality data from various locations.</p>

   <?php $cities = json_decode(file_get_contents('C:\xampp\htdocs\php\AirQualityDataBrowser\data\index.json'), true); 
    foreach ($cities as $city): ?>
    <li><a href="city.php?city=<?php echo urlencode($city['city']); ?>">
                <h2><?php echo htmlspecialchars($city['city']) . ' (' . $city['country'] . ')' . ' <img style = "width: 40px; height: 30px;" src="' . $city['flag'] . '" alt="' . $city['city'] . '">'; ?></h2>               
    </a></li>
          
     <?php endforeach; ?>

<?php require   'views/footer.inc.php'; ?>