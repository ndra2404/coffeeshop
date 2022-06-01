        <!-- recipe_menu_section - start
        ================================================== -->
        <section class="recipe_menu_section sec_ptb_120 bg_gray deco_wrap">
          <div class="container">
            <ul class="filters-button-group style_3 ul_li_center wow fadeInUp" data-wow-delay=".1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
              <li><button class="button text-uppercase active" data-filter="*">all</button></li>
							<?php
								foreach($kategori as $k){
									echo '<li><button class="button text-uppercase" data-filter=".'.$k->id_kategori.'">'.$k->kategori.'</button></li>';
								}
								?>
						</ul>

            <div class="recipe_item_grid grid wow fadeInUp" data-wow-delay=".2s" style="position: relative; height: 1020px; visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
              <?php
								foreach($menu as $row){
							?>
							<div class="element-item <?php echo $row->id_kategori?> " data-category="<?php echo $row->id_kategori?>" style="position: absolute; left: 0px; top: 0px;">
                <div class="recipe_item">
                  <div class="content_col">
                    <a class="item_image" href="shop_details.html">
                      <img src="<?php echo base_url()?>assets/images/<?php echo $row->foto?>" alt="image_not_found">
                    </a>
                    <div class="item_content">
                      <h3 class="item_title text-uppercase">
                        <a href="shop_details.html"><?php echo $row->nama_menu?></a>
                      </h3>
                      <p class="mb-0">
                        The coffee is brewed by first roasting the green coffee beans over hot coals in a brazier. Once the beans are roasted each participant is given an 
                      </p>
                    </div>
                  </div>
                  <div class="content_col ">
                    <strong class="item_price">
                      <sub>IDR</sub><?php echo number_format($row->harga)?>
                    </strong>
										
										<a class="btn btn_border btn-sm border_black text-uppercase" href="#!">Add To Cart</a>
                  </div>
									
                </div>
              </div>
								<?php
								}
								?>
            </div>
          </div>

          <div class="deco_item shape_1">
            <img src="<?php echo base_url()?>assets/images/menu/shape_01.png" alt="image_not_found">
          </div>
          <div class="deco_item shape_2">
            <img src="<?php echo base_url()?>assets/images/menu/shape_02.png" alt="image_not_found">
          </div>
        </section>
        <!-- recipe_menu_section - end
        ================================================== -->
