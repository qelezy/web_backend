<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $datetime = $_POST['datetime'];
  $duration = $_POST['duration'];
  $description = strip_tags($_POST['description']);
  if (!empty($datetime) && !empty($duration)) {
    $message = "Данные приняты на обработку";
    $data = [$datetime, $duration, $description];
    $file = fopen("training_sessions.csv", "a");
    fputcsv($file, $data, ";", "\"", "");
    fclose($file);
  } else {
    $message = "Заполните поля ввода";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <form method="post">
      <h2>Добавление тренировки</h2>
      <div class="form-body">
        <label>Дата и время<input type="datetime-local" name="datetime"></label>
        <label>Длительность<input type="number" name="duration"></label>
        <label>Описание<textarea name="description"></textarea></label>
      </div>
      <button type="submit">Добавить</button>
    </form>
    <p><?= $message ?></p>
  </main>
</body>
</html>