<?php
if (isset($_POST['selected_services'])) {
    $services = json_decode($_POST['selected_services'], true);
    if (is_array($services)) {
        echo "<h2>Selected Services:</h2>";
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Time (min)</th></tr>";
        foreach ($services as $srv) {
            // Safely echo values
            echo "<tr>";
            echo "<td>" . htmlspecialchars($srv['id']) . "</td>";
            echo "<td>" . htmlspecialchars($srv['name']) . "</td>";
            echo "<td>$" . htmlspecialchars($srv['price']) . "</td>";
            echo "<td>" . htmlspecialchars($srv['duration']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No valid services found.";
    }
} else {
    echo "No services selected.";
}
?>
