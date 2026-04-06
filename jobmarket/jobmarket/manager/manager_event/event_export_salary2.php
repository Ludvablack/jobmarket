<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu_setup</title>
</head>

<body>

    <H1>Export .CSV výplaty</H1>
    <!-- Plný export CSV-->
    < <hr />
    <?php
    // event_export_full2.php
    
    // Připojení k databázi
    include('../../connection.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Získání hodnot z POST
    $rok = isset($_POST['rok']) ? $_POST['rok'] : null;
    $mesic = isset($_POST['mesic']) ? $_POST['mesic'] : null;

    // Základ SQL
    $sql = "
 SELECT 
    YEAR(begin_event) AS rok,
    " . ($mesic !== "rok" ? "MONTH(begin_event) AS mesic," : "") . "
    id_worker,
    workername,
    birth_number,
    hpp,
    tax_event,
    SUM(hours) AS sum_hours,
    SUM(salary) AS sum_salary
 FROM event
 WHERE start_event IS NOT NULL
   AND stop_event IS NOT NULL
   AND start_event < '1970-01-01'
   AND stop_event < '1970-01-01'
   AND control = 'Ok'
   ";


    $params = [];
    $types = "";

    // rok
    if (!empty($rok)) {
        $sql .= " AND YEAR(begin_event) = ?";
        $params[] = $rok;
        $types .= "i";
    }

    // měsíc
    if (!empty($mesic) && $mesic !== "rok") {
        $sql .= " AND MONTH(begin_event) = ?";
        $params[] = $mesic;
        $types .= "i";
    }
    // GROUP BY – dynamické
    $sql .= "
    GROUP BY id_worker, rok
    ";

    if ($mesic !== "rok") {
        $sql .= ", mesic";
    }

    // ORDER BY – vzestupně podle období
    $sql .= "
    ORDER BY rok
    ";

    if ($mesic !== "rok") {
        $sql .= ", mesic";
    }

    $sql .= ", id_worker";



    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();


    // Nastavení hlaviček pro CSV stažení
    $filename = "export_" . date("Y-m-d_H-i-s") . ".csv";

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=$filename");

    // Otevření výstupu
    $output = fopen("php://output", "w");

    // UTF‑8 BOM pro Excel
    fwrite($output, "\xEF\xBB\xBF");
    if ($mesic === "rok") {
        // Roční export – BEZ sloupce Měsíc
        fputcsv($output, [
            "Rok",
            "ID pracovníka",
            "Název pracovníka",
            "Rodné číslo pracovníka",
            "HPP",
            "Daň",
            "Celkový počet hodin",
            "Celkový výdělek",
        ], ";");
    } else {
        // Měsíční export – se sloupcem Měsíc
        fputcsv($output, [
            "Rok",
            "Měsíc",
            "ID pracovníka",
            "Název pracovníka",
            "Rodné číslo pracovníka",
            "HPP",
            "Daň",
            "Celkový počet hodin",
            "Celkový výdělek",
        ], ";");
    }

    // Data
    while ($row = $result->fetch_assoc()) {

        if ($mesic === "rok") {
            // Roční export – BEZ měsíce
            fputcsv($output, [
                $row['rok'],
                $row['id_worker'],
                $row['workername'],
                $row['birth_number'],
                $row['hpp'],
                $row['tax_event'],
                $row['sum_hours'],
                $row['sum_salary'],
            ], ";");

        } else {
            // Měsíční export – s měsícem
            fputcsv($output, [
                $row['rok'],
                $row['mesic'],
                $row['id_worker'],
                $row['workername'],
                $row['birth_number'],
                $row['hpp'],
                $row['tax_event'],
                $row['sum_hours'],
                $row['sum_salary'],
            ], ";");
        }
    }



    fclose($output);
    $conn->close();
    exit;
    ?>



























</body>

</html>