<?php
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datetime = strip_tags($_POST['datetime']) ?? "";
    $duration = strip_tags($_POST['duration']) ?? "";
    $description = strip_tags($_POST['description']) ?? "";
    $response = ["message" => ""];
    if (!empty($datetime) && !empty($duration)) {
        $format = "Y-m-d\TH:i";
        $datetimeInput = DateTime::createFromFormat($format, $datetime);
        if (is_numeric($duration) && $datetimeInput && $datetimeInput->format($format) == $datetime) {
            if (floatval($duration) <= 0) {
                $response["message"] = "Длительность должна быть больше 0";
            } else {
                $data = [$datetime, $duration, $description];
                $file = fopen("training_sessions.csv", "a");
                fputcsv($file, $data, ";", "\"", "");
                fclose($file);
    
                $response["message"] = "Данные успешно обработаны";
            }
        } else {
            $response["message"] = "Введены некорректные данные";
        }
    } else {
        $response["message"] = "Заполните поля ввода";
    }
    echo json_encode($response);
}
?>