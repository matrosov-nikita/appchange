<?php include_once ROOT.'/views/layouts/header.php' ?>
<div class="container">
    <div class="main">
        <div class="row">
            <div class="col-md-12" id="container">
                <?php foreach ($adverts as $item) :?>
                        <?php include(ROOT."/views/advert/item.php"); ?>
                        <?php $time = date('H:i:s:m', time());
                              file_put_contents("log.txt",'подключаю адверт '.$time."\r\n",FILE_APPEND);
                        ?>
                <?php endforeach ;?>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="pages">

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>