<?php
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datetime = strip_tags($_POST["datetime"] ?? "");
    $duration = strip_tags($_POST["duration"] ?? "");
    $description = strip_tags($_POST['description'] ?? "");
    if (empty($datetime) || empty($duration)) {
        exit(json_encode(["message" => "Заполните поля ввода"]));
    }
    $format = "Y-m-d\TH:i";
    $datetimeInput = date_create_from_format($format, $datetime);
    $durationInput = floatval($duration);
    if (!$datetimeInput || !$durationInput) {
        exit(json_encode(["message" => "Введены некорректные данные"]));
    }
    if ($durationInput <= 0) {
        exit(json_encode(["message" => "Длительность должна быть больше 0"]));
    }
    $file = fopen("training_sessions.csv", "a");
    $data = [$datetime, $duration, $description];
    fputcsv($file, $data, ";", "\"", "");
    fclose($file);
    echo json_encode(["message" => "Данные успешно обработаны"]);
}
?>