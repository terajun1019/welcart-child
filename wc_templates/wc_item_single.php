<?php

/**
 * Product Page Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

get_header();
?>

<div id="primary" class="site-content">
  <div id="content" class="l-itemdetails" role="main">
    <!-- ****商品詳細***** -->
    <?php
    if (have_posts()) :
      the_post();
    ?>

      <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <!-- 商品タイトル -->
        <header class="item-header">
          <h2 class="item_page_title"><?php usces_the_itemName(); ?></h2>
          <div class="c-hr__fullwidth c-hr__lightbrown"> </div>
          <?php
          // ブランド名
          $catnames = get_the_category();
          foreach ($catnames as $catname) {
            if (get_category($catname->parent)->slug == "brand") {
              echo '<h3 class="itembrand">';
              echo $catname->name;
              echo '</h3>';
              break;
            }
          }
          ?>

        </header><!-- .item-header -->

        <div class="storycontent">

          <?php usces_remove_filter(); ?>
          <?php usces_the_item(); ?>
          <?php usces_have_skus(); ?>

          <div id="itempage">

            <div id="img-box">

              <div class="itemimg">
                <a href="<?php usces_the_itemImageURL(0); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', null); ?>><?php usces_the_itemImage(0, 335, 335, $post); ?></a>
                <?php do_action('usces_theme_favorite_icon'); ?>
              </div>

              <?php
              $imageid = usces_get_itemSubImageNums();
              if (!empty($imageid)) :
              ?>
                <div class="itemsubimg">
                  <?php foreach ($imageid as $id) : ?>
                    <a href="<?php usces_the_itemImageURL($id); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', null); ?>><?php usces_the_itemImage($id, 120, 120, $post); ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

            </div><!-- #img-box -->
            <div class="l-item__info-wrapper">
              <div class="detail-box">
                <h2 class="item-name"><?php usces_the_itemName(); ?></h2>
                <div class="itemcode">(<?php usces_the_itemCode(); ?>)</div>
                <div class="itemprice c-text__brown-d">
                  <?php
                  // 商品価格表示
                  $priceMax = 0;
                  $priceMin = 0;
                  do {
                    // echo var_export(usces_the_itemPriceCr(),true);
                    // usces_guid_tax();
                    $priceTemp = intval(usces_the_itemPrice_taxincluded('return'));
                    if ($priceMax == 0 && $priceMin == 0) {
                      $priceMax = $priceTemp;
                      $priceMin = $priceTemp;
                    } else {
                      $priceMax = ($priceMax < $priceTemp) ? $priceTemp : $priceMax;
                      $priceMin = ($priceMin > $priceTemp) ? $priceTemp : $priceMin;
                    }
                  } while (usces_have_skus());
                  if ($priceMax == $priceMin) {
                    echo number_format($priceMin) . '円';
                  } else {
                    echo number_format($priceMin) . ' ～ ' . number_format($priceMax) . '円';
                  }
                  // 税込表記
                  usces_guid_tax();
                  // skuリセット
                  usces_reset_skus();
                  usces_have_skus(); //skuループ時は初回にコレを１回呼んだ状態から開始する
                  ?>
                </div>

                <div class="c-hr__fullwidth c-hr__brown mb-5"> </div>

                <!-- 在庫選択フォーム -->
                <form action="<?php echo USCES_CART_URL; ?>" method="post">

                  <div class="itemChart clay mb-5">
                    <?php $cnt = 1; ?>
                    <?php do { ?>
                      <?php if ($cnt == 1) : ?>
                        <div class="itemchart-row itemchart-header">
                          <div class="itemchart-col w-30">サイズ</div>
                          <div class="itemchart-col w-20">数量</div>
                          <div class="itemchart-col w-25 itemchart-col__tax">価格(税込)</div>
                          <div class="itemchart-col w-25"></div>
                        </div>
                      <?php endif; ?>
                      <div class="itemchart-row itemchart__sku">
                        <div class="itemchart-col w-35">
                          <!-- sku -->
                          <?php if ('' !== usces_the_itemSkuDisp('return')) : ?>
                            <div class="skuname"><?php usces_the_itemSkuDisp(); ?></div>
                          <?php endif; ?>
                        </div>
                        <div class="itemchart-col itemchart__zaiko w-20">
                          <!-- zaiko -->
                          <?php
                          if (usces_have_zaiko()) {
                            // usces_the_itemZaikoNum();
                            usces_the_itemQuant();
                          } else {
                            echo "在庫なし";
                          }
                          ?>
                        </div>
                        <div class="itemchart-col itemchart__price w-25">
                          <!-- price -->
                          <?php if (usces_the_itemCprice('return') > 0) : ?>
                            <!-- sale price -->
                            <div class="field_cprice"><?php usces_the_itemCpriceCr(); ?></div>
                            <div class="field_lprice c-text__linethrough"><?php usces_the_itemPriceCr(); ?></div>
                          <?php else: ?>
                          <!-- list price -->
                            <div class="field_lprice"><?php usces_the_itemPriceCr(); ?></div>
                          <?php endif; ?>
                          <?php usces_guid_tax(); ?>
                        </div>
                        <div class="itemchart-col w-20">
                          <!-- cart button -->
                          <!-- <?php //usces_direct_intoCart( 132, "bobo_119141-8-9years" ); 
                                ?> -->
                          <?php usces_the_itemSkuButton(__('Add to Shopping Cart', 'usces'), 0); ?>
                          <!-- <input name="inCart[132][bobo_119141-8-9years]" type="submit" id="inCart[132][bobo_119141-8-9years]" class="skubutton" value="カートへ入れる" onclick="return uscesCart.intoCart('132','bobo_119141-8-9years')"> -->
                        </div>
                      </div>
                      <?php $cnt += 1; ?>
                    <?php } while (usces_have_skus()); ?>

                  </div>
                </form>

                <?php wel_campaign_message(); ?>
                <div class="item-description">
                  <?php the_content(); ?>
                </div>

                <?php if ('continue' === wel_get_item_chargingtype()) : ?>
                  <!-- Charging Type Continue shipped -->
                  <div class="field">
                    <table class="dlseller">
                      <tr>
                        <th><?php _e('First Withdrawal Date', 'dlseller'); ?></th>
                        <td><?php echo dlseller_first_charging($post->ID); ?></td>
                      </tr>
                      <?php if (0 < (int) $usces_item['dlseller_interval']) : ?>
                        <tr>
                          <th><?php _e('Contract Period', 'dlseller'); ?></th>
                          <td><?php echo $usces_item['dlseller_interval']; ?><?php _e('month (Automatic Updates)', 'welcart_basic'); ?></td>
                        </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                <?php endif; ?>
              </div><!-- .detail-box -->


              <?php
              // skuリセット
              usces_reset_skus();
              usces_have_skus();
              ?>
              <!-- 在庫選択フォーム ここまで -->

              <!-- <div class="item-info">
								<?php
                $item_custom = usces_get_item_custom($post->ID, 'list', 'return');
                if ($item_custom) {
                  echo $item_custom;
                }
                ?>
								<form action="<?php echo USCES_CART_URL; ?>" method="post">

									<?php do { ?>
										<div class="skuform">
											<?php if ('' !== usces_the_itemSkuDisp('return')) : ?>
												<div class="skuname"><?php usces_the_itemSkuDisp(); ?></div>
											<?php endif; ?>

											<?php if (usces_is_options()) : ?>
												<dl class="item-option">
													<?php while (usces_have_options()) : ?>
														<dt><?php usces_the_itemOptName(); ?></dt>
														<dd><?php usces_the_itemOption(usces_getItemOptName(), ''); ?></dd>
													<?php endwhile; ?>
												</dl>
											<?php endif; ?>

											<?php usces_the_itemGpExp(); ?>

											<div class="field">
												<div class="zaikostatus"><?php _e('stock status', 'usces'); ?> : <?php usces_the_itemZaikoStatus(); ?></div>

												<?php if ('continue' === wel_get_item_chargingtype()) : ?>
													<div class="frequency"><span class="field_frequency"><?php dlseller_frequency_name($post->ID, 'amount'); ?></span></div>
												<?php endif; ?>

												<div class="field_price">
													<?php if (usces_the_itemCprice('return') > 0) : ?>
														<span class="field_cprice"><?php usces_the_itemCpriceCr(); ?></span>
													<?php endif; ?>
													<?php usces_the_itemPriceCr(); ?><?php usces_guid_tax(); ?>
												</div>
												<?php usces_crform_the_itemPriceCr_taxincluded(); ?>
											</div>

											<?php if (!usces_have_zaiko()) : ?>
												<div class="itemsoldout"><?php echo apply_filters('usces_filters_single_sku_zaiko_message', __('At present we cannot deal with this product.', 'welcart_basic')); ?></div>
											<?php else : ?>
												<div class="c-box">
													<span class="quantity"><?php _e('Quantity', 'usces'); ?><?php usces_the_itemQuant(); ?><?php usces_the_itemSkuUnit(); ?></span>
													<span class="cart-button"><?php usces_the_itemSkuButton('&#xf07a;&nbsp;&nbsp;' . __('Add to Shopping Cart', 'usces'), 0); ?></span>
												</div>
											<?php endif; ?>
											<div class="error_message"><?php usces_singleitem_error_message($post->ID, usces_the_itemSku('return')); ?></div>
										</div>
									<?php } while (usces_have_skus()); ?>

									<?php do_action('usces_action_single_item_inform'); ?>
								</form>
								<?php do_action('usces_action_single_item_outform'); ?>

							</div> -->
              <!-- .item-info -->
            </div>

            <?php usces_assistance_item($post->ID, __('An article concerned', 'usces')); ?>

          </div><!-- #itemspage -->
        </div><!-- .storycontent -->

      </article>

    <?php else : ?>
      <p><?php _e('Sorry, no posts matched your criteria.', 'usces'); ?></p>
    <?php endif; ?>

  </div><!-- #content -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>