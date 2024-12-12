<?php

$lines_per_page = 10;

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

$file = "./data/data.csv";
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    $data = [];
    foreach ($lines as $line) {
        $data[] = explode(",", $line);
    }

    usort($data, function ($a, $b) {
        return strtotime($a[0]) - strtotime($b[0]);
    });

    $start = ($page - 1) * $lines_per_page;
    $page_data = array_slice($data, $start, $lines_per_page);

    $total_amount = 0;
    foreach ($data as $row) {
        $amount = (float)$row[3];
        if ($row[2] == 'OUT') {
            $total_amount -= $amount; // expenseはマイナス
        } else if ($row[2] == 'IN') {
            $total_amount += $amount; // incomeはプラス
        }
    }


    $total = number_format($total_amount);
    $total_pages = ceil(count($data) / $lines_per_page);

    $date = date("Y年m月d日(D)");

    echo '<div style="text-align:center;">' . $date . "</div>";
    echo '<div style="text-align:center; font-size:64px;">' . $total . "円" . "</div>";

    echo "<div style='width: 100%; display: flex; justify-content: center;'>
            <table border='1' style='max-width: 800px; width: 100%; border-collapse: collapse;'>
            <tr><th>Date</th><th>Item</th><th>Amount</th></tr>";


    foreach ($data as $row) {

        $formatted_amount = number_format($row[3]);
        if ($row[2] == 'OUT') {
            $formatted_amount = '-' . number_format($row[3]);
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row[0]) . "</td>";
        echo "<td>" . htmlspecialchars($row[1]) . "</td>";
        echo "<td>" . $formatted_amount . "</td>";
        echo "</tr>";
    }

    echo "</table></div>";
    echo "<div style='margin-top: 20px; text-align: center;'>";
    if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "'>&laquo; Previous</a> ";
    }
    if ($page < $total_pages) {
        echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
    }

    echo "</div>";
} else {
    echo "no file found.";
}
