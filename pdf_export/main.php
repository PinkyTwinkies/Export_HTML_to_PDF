<?php
require "vendor/autoload.php";
use Dompdf\Dompdf;
$pdo = new PDO("mysql:host=127.0.0.1;dbname=employee", "root");
$stmt = $pdo->prepare("SELECT firstname, lastname FROM employee");
$stmt->execute();
$table = "
<html>
<head>
<link type=\"text/css\" href=\"style.css\" rel=\"stylesheet\"/>
</head>
<body>
    <table>
    <thead>
    <tr>
      <th>FirstName</th>
      <th>Surname</th>
    </tr>
  </thead>
  <tbody>";
while ($row = $stmt->fetch()) {
    $table .= "<tr><td>" . $row["firstname"] . '</td><td>' . $row["lastname"] . "</td></tr>";
}
$table .= "</tbody></table></body></html>";
echo $table;
if (isset($_POST['print'])) {
    $dompdf = new DOMPDF();
    $dompdf->loadHtml(html_entity_decode($table));
    $dompdf->render();
    ob_end_clean();
    $dompdf->stream("employees.pdf");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HTML to PDF</title>

    <link type="text/css" href="style.css" rel="stylesheet"/>
</head>
<body>
<form name="printingform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <button type="submit" name="print" style="margin: 10px">Export to PDF</button>
</form>
</body>
</html>