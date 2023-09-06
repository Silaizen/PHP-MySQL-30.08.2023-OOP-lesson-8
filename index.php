<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("X-Content-Type-Options: nosniff");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
        }

        h2 {
            margin-top: 20px;
            color: #007bff;
        }

        form {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="number"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 5px;
        }
    </style>
    <title>Банкомат</title>

</head>
<body>
    <h1>Банкомат</h1>

    <?php
    require_once 'ATM.php';

  
    $atm = new ATM();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['amount'])) {
        
        $amount = $_POST['amount'];
        $withdrawn = $atm->withdrawMoney($amount);

        if (is_array($withdrawn)) {
            echo "<p>Снято следующее количество купюр:</p>";
            echo "<ul>";
            foreach ($withdrawn as $denomination => $count) {
                echo "<li>$denomination грн: $count купюр</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>$withdrawn</p>";
        }
    } else {
       
        echo "Сумма не указана.";
    }
    }
    ?>

    <form method="post">
        <label for="amount">Сумма для снятия:</label>
        <input type="number" id="amount" name="amount" required min="10">
        <button type="submit">Снять деньги</button>
    </form>

    <hr>

    <h2>Управление банкоматом</h2>
    <form method="post">
        <label for="minAmount">Минимальная сумма для снятия:</label>
        <input type="number" id="minAmount" name="minAmount" min="1" value="<?php echo $atm->getMinWithdrawalAmount(); ?>">
        <button type="submit" name="setMinAmount">Установить</button>
    </form>

    <form method="post">
        <label for="maxNotes">Максимальное количество банкнот для снятия:</label>
        <input type="number" id="maxNotes" name="maxNotes" min="1" value="<?php echo $atm->getMaxWithdrawalNotes(); ?>">
        <button type="submit" name="setMaxNotes">Установить</button>
    </form>

    <form method="post">
        <label for="addDenomination">Номинал банкноты:</label>
        <select id="addDenomination" name="addDenomination">
            <option value="500">500 грн</option>
            <option value="200">200 грн</option>
            <option value="100">100 грн</option>
            <option value="50">50 грн</option>
            <option value="20">20 грн</option>
            <option value="10">10 грн</option>
            <option value="5">5 грн</option>
            <option value="2">2 грн</option>
            <option value="1">1 грн</option>
        </select>
        <label for="addCount">Количество банкнот:</label>
        <input type="number" id="addCount" name="addCount" min="1" value="1">
        <button type="submit" name="addMoney">Добавить купюры</button>
    </form>

    <hr>

    <h2>Количество банкнот</h2>
    <?php
    if (isset($_POST['addMoney'])) {
       
        $denomination = $_POST['addDenomination'];
        $count = $_POST['addCount'];
        $atm->loadMoney($denomination, $count);
    }

    echo "<ul>";
    foreach ($atm->getNoteCounts() as $denomination => $count) {
        echo "<li>$denomination грн: $count купюр</li>";
    }
    echo "</ul>";
    ?>
</body>
</html>