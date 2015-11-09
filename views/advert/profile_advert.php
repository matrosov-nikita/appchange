<?php include_once ROOT.'/views/layouts/header.php' ?>

<?php foreach ($my_adverts as $item) :?>
    <?php
    $list = Category::getListCategories();
    ?>
    <form  class="edit_advert">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-8 col-sm-12 ad">
                <div class="row">

                    <div class="col-md-1 col-sm-1 col-xs-2">
                        <i class="fa fa-plus-circle plus"></i>
                    </div>
                    <div class="col-md-11 col-sm-11 col-xs-10">

                        <div class="title">
                            <input  type="text" value="<?php  echo $item['header'];?>" name='header'>
                        </div>
                    </div>
                </div>
                <span class="time" name="date_public"><?php
                    $dt = new DateTime($item['date_public']);
                    echo $dt->format('d.m.Y H:m');
                    ?></span>
                <select name="category" class="categor">
                    <?foreach($list as $row):?>

                        <option <?echo "value={$row['id']}";?> <? if ($row['id']==$item['category']) echo "selected='selected'"; ?>> <?echo $row['name'];
                            ?>

                        </option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="col-md-7 contents">
                <textarea rows="6" name="description" ><?php echo $item['description'] ;?></textarea>
                <div class="images">
                    <div class="row">
                        <div class="col-md-12 carousel">

                            <?php foreach (Advert::getAllImagesByID($item['id']) as $image) :?>
                                <?php
                                echo "<div href='".$image['image']."' target='_blank' class='col-md-4 col-sm-4 col-xs-4 item fancybox'>
                        <img src='".$image['image']."' ></div>";?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
					<span class="author">
						<?php echo User::getLoginById($item['author']);?>
					</span>
                <input type="text" class="hidden" name="id" value="<?php echo $item['id']; ?>">
                <button class="btn btn-success save" type="submit">Сохранить</button>

            </div>

        </div>
    </div>
</div>
    </form>
<? endforeach; ?>