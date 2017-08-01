<html>
<head>
    <meta charset="UTF-8">
    <title>Ваш профиль</title>
</head>
<body>
<div style="text-align: center;">
    <h2>Добро пожаловать!</h2>
    <p>Здесь вы можете отредактировать Ваши данные, если Вам это необхожимо!</p>
    <p>На даный момент, Ваш логин - <span><?php echo $_SESSION['login'];?></span>.</p>
    <p>Если Вы хотите изменить его, или другие данные - заполните форму, и нажмите "Редактировать"!<p>
        <form action="" method="POST">
    <p>Изменить логин : </p>
    <label>
        <input type="text" name="login">
    </label>
    <br><button type="submit" name="submit">Редактировать логин!</button>
    <p>Изменить пароль :</p>
    <label>
        <input type="password" name="password">
    </label>
    <br><button type="submit" name="submit2">Редактировать пароль!<? echo $test; ?></button>
    <p>Изменить Ф.И.О :</p>
    <label>
        <input type="text" name="fio">
    </label>
    <br><button type="submit" name="submit3">Редактировать Ф.И.О!</button>
</div>
</body>
</html>

<div style="text-align: center;"><p><a href="logout">Выйти</a> из системы</p></div>