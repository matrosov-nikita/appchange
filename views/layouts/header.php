<?php
$list = Category::getListCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Доска объявлений</title>
    <link rel="stylesheet" href="/app/libs/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/app/css/main.css"/>
    <link rel="stylesheet" href="/app/libs/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/app/css/fonts.css"/>
    <link rel="stylesheet" href="/app/libs/fancybox/source/jquery.fancybox.css"/>
    <link rel="stylesheet" href="/app/libs/alertify/alertify.core.css">
    <link rel="stylesheet" href="/app/libs/alertify/alertify.default.css">
    <link rel="stylesheet" href="/app/libs/magnific/magnific-popup.css">
    <link rel="stylesheet" href="/app/libs/owl-carousel/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="/app/libs/owl-carousel/owl-carousel/owl.theme.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="row">
                <div class="col-md-12">
                   <?php if (User::checkUser()): ;?>
                    <div class="col-md-3">
                    <p>Добро пожаловать,
                    <span> <?php echo $_COOKIE['login'];?> </span>
                    </p>
                    </div>
                    <div class="col-md-5 col-md-push-4">
                        <a  href='/profile' class="btn btn-default profile"> Ваш профиль </a>
                        <a href="#create" class="btn btn-danger fancybox">Создать объявление</a>
                        <a href="/logout" class="btn btn-default fancybox">Выход</a>
                    </div>
                    <?php else: ?>
                       <div class="col-md-3">
                           <p>Добро пожаловать,
                               <span>Гость </span>
                           </p>
                       </div>
                    <div class="col-md-3 col-md-push-6">
                        <a href="#enter" class="btn btn-default fancybox">Вход</a>
                        <a href="#register" class="btn btn-default fancybox">Регистрация</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </header>

    </div>

<div class="hidden">
    <form  id="register" >
        <h4>Регистрация</h4>
        <input  class="form-control"  type="text" name="login" placeholder="Логин" required>
        <span class="login_errors"></span>
        <input  class="form-control"  type="password" name="password" placeholder="Пароль" required>
        <span class="pass_errors"></span>
        <input  class="form-control"  type="email" name="email" placeholder="E-mail" required>
        <span class="email_errors"></span>
        <input  class="form-control"  type="text" name="company_name" placeholder="Имя компании" required>
        <span></span>
        <div class="button_wrap">
            <input class="btn btn-success" type="submit" value="Регистрация" name="register" >
        </div>

    </form>

    <form id="enter">
        <h4>Авторизация</h4>
        <input class="form-control" type="text" name="login" placeholder="Логин" required>
        <span class="login_errors"></span>
        <input class="form-control"  type="password" name="password" placeholder="Пароль" required>
        <span class="pass_errors"></span>
        <div class="button_wrap">
        <input class="btn btn-success" value ="Войти" type="submit" name="enter">
            </div>
    </form>
        <form id="create" action="/create" enctype="multipart/form-data"  method="post">
            <h4>Создание объявления</h4>
            <input class="form-control" name='header' type="text" placeholder="Заголовок" required>
            <textarea name="description"   rows="5" class="form-control" placeholder="Ваше объявление" required></textarea>
            <select name="category" class="form-control">

                <?foreach($list as $row):?>
                    <option <?echo "value={$row['id']}";?>> <?echo $row['name'];?></option>
                <?endforeach;?>
            </select>
            <input type="file" name="upload[]" multiple/>

            <div class="button_wrap">
                <button form="create" class="btn btn-success" name="create"> Сохранить</button>
            </div>
        </form>


</div>
<script type="text/javascript" src="/app/libs/jquery/dist/jquery.min.js"></script>
<script src="/app/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/app/libs/fancybox/source/jquery.fancybox.js"></script>
<script src="/app/libs/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="/app/js/main.js"></script>
<script src="/app/libs/alertify/alertify.js"></script>
    <script src="/app/libs/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/app/libs/owl-carousel/owl-carousel/owl.carousel.min.js"></script>
    <script src="/app/libs/masonry/masonry.pkgd.min.js"></script>
    <script src="/app/libs/imagesloaded/imagesloaded.pkgd.min.js"></script>
</body>
</html>