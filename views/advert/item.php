<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12 container_item  <?php echo $item['id']; ?>'>
    <div class='advert'>
        <div class='top_line'>

                            <span class='date'>
                            <?php
                            $dt = new DateTime($item['date_public']);
                            echo $dt->format('d.m.Y');
                            ?>
    </span>
            <h4><?
                  if (strlen($item['header'])>40):
                      echo substr($item['header'],0,40)."...";
                  else:
                      echo  $item['header'];
                  endif;
                ?>
               </h4>
        </div>
        <div class='content'>
            <div class='description'><?echo $item['description'];?></div>
            <strong>Категория: <?echo Category::getCategoryById($item['category']);?>
            </strong>
                        <span class='time'>
                              <?php
                              $dt = new DateTime($item['date_public']);
                              echo $dt->format('H:m');
                              ?>
                        </span>
            <div class='user_block'>
                <a  class='visitors' title='Просмотры'>
                    <i class='fa fa-user'></i>
                    <span class='count'> <?echo $item['watches'];?> </span>
                </a>
                <input type="text" class="hidden" value="<?php echo $item['id'] ?>">
                <div class='button_wrap'>
                    <button  href='#<?php echo $item['id'] ?>' class='btn btn-sm btn-primary fancybox detail'> Подробнее... </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <form id="<?php echo $item['id'] ?>" class="g" >
        <div class="container main" style="width: 600px">
            <div class="row">
                <div class="col-md-12">
                    <h4>
                        <? echo $item['header'];?>
                    </h4>
        <span>Размещено:<?php
            $dt = new DateTime($item['date_public']);
            echo $dt->format('d.m.Y H:m');
            ?></span>
                    <div class="set_images">
                        <div class="container" style="width: 600px">
                            <div class="row">
                                <div class='col-md-12 masonry-container '>
                                    <?php foreach (Advert::getAllImagesByID($item['id']) as $image) :?>
                                        <?php
                                        echo "<div href='".$image['image']."' target='_blank' class='col-md-4 col-sm-4 col-xs-6 item fancybox'>
                        <img src='".$image['image']."' ></div>";?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span> Автор: <?php echo User::getLoginById($item['author']);?> </span>
                    <p><?echo $item['description'];?></p>
                </div>
            </div>
        </div>

    </form>
</div>

