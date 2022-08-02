<?php

/**
 * FROCSSスタイルシート読み込み
 */
function lb_enqueue_styles()
{
    $cssFiles = array("foundation", "layout", "project", "component" );

    foreach ($cssFiles as $cssFile) {
        $mtime = filemtime(get_theme_file_path() . '/css/' . $cssFile.'.css');
        wp_enqueue_style($cssFile, get_stylesheet_directory_uri() . '/css/' . $cssFile.'.css', array(), $mtime);
    }
}
add_action('wp_enqueue_scripts', 'lb_enqueue_styles', 20);

function lb_enqueue_scripts()
{
    $mtime = filemtime(get_theme_file_path() . '/js/swiper.js');
    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/js/swiper.js', array('jquery'), $mtime, true);
}
add_action('wp_enqueue_scripts', 'lb_enqueue_scripts', 30);


/* ---------- カスタム投稿タイプを追加 ---------- */
function lb_create_post_type()
{
    // register_post_type(
    //     'news',
    //     array(
    //         'label' => 'お知らせ',
    //         'singular_name' => 'お知らせ',
    //         'all_items' => 'お知らせ一覧',
    //         'view_items' => 'お知らせを見る',
    //         'public' => true,
    //         'has_archive' => true,
    //         'menu_position' => 5,
    //         'supports' => array(
    //             'title',
    //             'editor',
    //             'thumbnail',
    //             'revisions',
    //             'excerpt',
    //         ),
    //     )
    // );

    //管理画面で表示される
    $labels = array(
        'name' => 'お知らせ',
        'singular_name' => 'お知らせ',
        'add_new' => '新規お知らせ',
        'add_new_item' => '新規お知らせを追加',
        'all_items' => _x('お知らせ一覧', 'sample'),
        'edit_item' => _x('お知らせを編集', 'sample'),
        'new_item' => _x('新しいお知らせ', 'sample'),
        'view_item' => _x('お知らせを見る', 'sample'),
        'search_items' => _x('お知らせを検索', 'sample'),
        'not_found' => _x('お知らせが見つかりません', 'sample'),
        'not_found_in_trash' => _x('ゴミ箱にお知らせはありません', 'sample'),
        'parent_item_colon' => _x('親お知らせ:', 'sample'),
        'menu_name' => _x('お知らせ', 'sample'),
    );

    //投稿タイプの種類やサポート
    $args = array(
        'labels'              => $labels,
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky'),
        'public'              => true,
        'show_ui'            => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'  => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'has_archive'        => true,
        'query_var'          => true,
        'can_export'          => true,
        'rewrite'            => array('slug' => 'news'),
        'capability_type'    => 'post',
        'menu_icon'  => 'dashicons-format-aside', //Wordpress Dashicons参照
    );

    register_post_type('news', $args);

    register_taxonomy(
        'news-cat',
        'news',
        array(
            'hierarchical' => true,
            'update_count_callback' => '_update_post_term_count',
            'label' => 'カテゴリー',
            'singular_label' => 'カテゴリー',
            'public' => true,
            'show_ui' => true
        )
    );

    register_taxonomy(
        'news-tag',
        'news',
        array(
            'label' => 'タグ',
            'hierarchical' => false,
            'public' => true,
        )
    );
}
add_action('init', 'lb_create_post_type');


/**
 * ヘッダメニューの個別フィルタ
 */
function lb_custom_topfixedmenu($items, $args)
{
    if (is_object($args->menu)) {
        if ($args->menu->name == 'footer menu a') {
            // フッターメニュー用処理
            $items = preg_replace('/<li id=.+>.*<a href=.+>メニュー区切り線<\/a><\/li>/', '<hr>', $items, -1);
            return $items;
        }elseif ($args->menu->name <> 'header fixed menu') {
            // トップ固定メニュー以外は処理しない
            return $items;
        }
    } else {
        if ($args->menu <> 'header fixed menu') {
            return $items;
        }
    }
    // ボタン別画像ファイル名配列
    $menuiconUrls = array(
        'ブランド' => 'button-icon_brand.png',
        'アイテム' => 'button-icon_item.png',
        '新商品' => 'button-icon_new.png',
        '検索' => 'button-icon_search.png',
        'Instagram' => 'button-icon_insta.png',
    );
    // トップ固定メニューをボタン別にスプリット
    $items = preg_replace('/>[	 \n]*<li /', '>$$<li ', $items, -1, $cnt);
    $item_units = explode('$$', $items);
    // ボタン名をキーにボタンアイコン画像ファイル名を入れ込み
    foreach ($item_units as &$item_unit) {
        foreach ($menuiconUrls as $key => $value) {
            if (strpos($item_unit, '<p>' . $key . '</p>') > 0) {
                $item_unit = str_replace('$key$', get_stylesheet_directory_uri() . '/images/' . $value, $item_unit);
                break;
            }
        }
    }
    // 最後に結合
    $items = implode($item_units);

    return $items;
}
add_filter('wp_nav_menu_items', 'lb_custom_topfixedmenu', 30, 2);


/**
 * コンテンツタイトル作成
 */
function lb_draw_content_title($caption_jp, $caption_en)
{
    ?>
    <h1 class="c-content__title">
        <div class="c-text__title-jp"><?php echo $caption_jp;?></div>
        <hr>
        <div class="c-text__title-en"><?php echo $caption_en;?></div>
    </h1>
    <?php
}

/**
 * カテゴリ商品のリスト出力
 */
function show_postlist($args = false)
{
    if(!$args) {
        // argsデフォルト値
        $args = array(
            'id' => 'top-newitem',
            'slug' => 'itemnew',
            'color' => 'peach',
            'title' => true,
            'title-jp' => 'タイトル',
            'title-en' => 'title',
            'msg' => '',
            'swiper' => true,
            'row' => 2,
            'disp-max' => 3,
            'seemore' => true,
            'clay' => false,
        );
    }
    $slug = $args['slug'];
    $msg = $args['msg'];
    $clay = $args['clay'];
    $row = $args['row'];
    ?>
	<div <?php echo ($args['id']?'id="'.$args['id'].'"':''); ?> class="l-content__wrapper <?php echo ($args['color']?'l-content__col-'.$args['color']:''); ?>">
		<!-- content title -->
        <?php lb_draw_content_title($args['title-jp'], $args['title-en']); ?>
		<!-- content -->
        <?php if($slug == 'itemnew'): ?>
		<div class="swiper-newitem">
			<div class="swiper-wrapper">
        <?php else: ?>
        <div class="l-content__flex <?php echo ($clay?'card':''); ?>">
        <?php endif; ?>
            <?php
            
            if ($msg == '') {
                $cat_info = get_category_by_slug($slug);
                $msg = $cat_info->name;
            }
            $msg = '<i class="fas fa-grip-lines-vertical" style="color:blue;"></i>&nbsp' . $msg;
            $args2 = array(
                'posts_per_page'    => $args['disp-max'],
                'post_status'       => array('publish'),
                'category_name'     => $slug,
                'orderby'           => 'date',
                'order'             => 'desc',
            );
            $posts = new WP_Query($args2); 
            ?>
            <?php while ($posts->have_posts()) : $posts->the_post(); ?>
            
            <!-- 新商品の出力時は新商品カテゴリのアイテムを抽出 -->
            <?php if($slug == 'itemnew'): ?>
                <div class="<?php echo ($args['swiper'] ? 'swiper-slide' : $slug); ?>">
                    <?php usces_the_item(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="itemimg">
                            <a href="<?php the_permalink() ?>">
                                <?php usces_the_itemImage(0, 100, 100); ?>
                            </a>
                            <?php welcart_basic_campaign_message();  ?>
                        </div>
                        <?php if (!usces_have_zaiko_anyone()) : ?>
                            <div class="itemsoldout">
                                <?php _e('Sold Out', 'usces'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($slug == 'itemnew') : ?>
                        <!-- 新商品スライダー用 個別キャプション文言 -->
                        <div class="itemdescription c-text__white c-text__bold">
                            <?php
                            // ブランド名
                            $catnames = get_the_category();
                            foreach ($catnames as $catname) {
                                if (get_category($catname->parent)->slug == "brand") {
                                    echo '<div class="itembrand">';
                                    echo $catname->name;
                                    echo '</div>';
                                    break;
                                }
                            }
                            ?>
                            <div class="itemname">
                                <a href="<?php the_permalink() ?>" rel="bookmark" class="c-text__white">
                                    <?php usces_the_itemName(); ?>
                                </a>
                            </div>
                            <div class="itemprice c-text__white">
                                <?php usces_the_firstPriceCr();
                                usces_guid_tax(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    </article>
                </div>
                <?php else: ?>
                    <!-- 新商品以外のコンテンツのアイキャッチたち -->
                    <a href="<?php echo get_the_permalink() ?>" class="l-content__flex-child50 <?php echo ($clay?'clay c-text__white':''); ?>" <?php echo 'style="width:calc(100% / '.$row.' - 3vw);"'; ?>>
                        <!-- サムネイル -->
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>">
                        <!-- タイトル -->
                        <div class="c-text__bannercaption-wrapper c-text__1stbig mb-2">
                            <span class=""><?php echo get_the_title(); ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endwhile; ?>

            <?php if($slug == 'itemnew'): ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if($args['seemore']) :?>
            <?php
            $cat = get_category_by_slug($slug);
            $morelink = get_category_link($cat);
            ?>
            <div class="l-content__footer c-button__xxx">
                <a href="<?php echo $morelink; ?>" class="l-content__footerbtn clay card peach-d">もっとみる
                </a>
            </div>
        <?php endif; ?>
	</div>

    <?php
    wp_reset_postdata();    /* ⑥WP_Queryのリセット */
}


/**
 * カテゴリ一覧作成
 */
function show_category_list($catname)
{
    $catid = get_cat_ID($catname);
    // カテゴリ名からＩＤは取得できる。スラッグにしなくても良いか？要検討
    $cats = get_terms('category', array(
        'orderby' => 'count',
        'hide_empty' => 0,
        'child_of' => $catid,
    ));

    if ($cats) {
    ?>
        <div id="top-newitem" class="l-content__wrapper l-content__col-lawn">
            <!-- content title -->
            <?php lb_draw_content_title('サイズ', 'size'); ?>

            <!-- content -->
            <div class="l-content__wrapper">
                <div class="l-content__flex card">
                <?php
                foreach ($cats as $cat) {
                    if ($cat->parent) {
                ?>
                <a href="<?php echo get_term_link($cat); ?>" class="l-content__flex-col c-linkbutton clay white">
                    <span class="c-linkbutton__label-jp c-text__1stbig"><?php echo $cat->name; ?></span>
                    <hr>
                    <span class="c-linkbutton__label-en"><?php echo $cat->slug; ?></span>
                </a>
                <?php
                    }
                }
                ?>
                </div>
            </div>
        </div>
    <?php
    }
}

// カテゴリバナー表示
function show_category_pagebanner($slug)
{
    $postobj = get_post(get_page_by_path($slug));

    if ($postobj) {
    ?>
        <div class="l-content__wrapper l-content__col-aqua">
            <!-- content title -->
            <?php lb_draw_content_title($postobj->post_title, $postobj->post_name); ?>

            <!-- content -->
            <div class="l-content__wrapper card">
                <a href="<?php echo get_the_permalink($postobj); ?>">
                    <div class="c-pagebanner__thumb-wrapper clay">
                        <div class="c-text__bannercaption-wrapper c-text__white c-text__bold c-text__1stbig">
                            <div class="c-text__bannercaption-jp"><?php echo get_post_meta($postobj->ID, 'bannercaption-jp', true); ?></div>
                            <div class="c-text__bannercaption-en"><?php echo get_post_meta($postobj->ID, 'bannercaption-en', true); ?></div>
                        </div>
                        <div class="c-pagebanner__thumb-cover"></div>
                        <?php
                        echo get_the_post_thumbnail($postobj, 'post-thumbnail', array('class' => 'c-pagebanner__thumb'));
                        ?>
                    </div>
                </a>
            </div>
        </div>
    <?php
    }
}

// お知らせ表示
function show_newsline($posttype)
{
    $args = array(
        'post_type' => $posttype,
        'post_status' => array('publish'),
        'order' => 'desc',
        'orderby' => 'post_date',
        'posts_per_page' => '3'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
    ?>
        <div id="top-newitem" class="l-content__wrapper l-content__col-pink">
            <!-- content title -->
            <?php lb_draw_content_title('お知らせ', 'news'); ?>

            <!-- content -->
            <div class="l-content__wrapper">
                <div class="card">
                    <?php
                    while ($query->have_posts()) {
                        //次の投稿へ進む
                        $query->the_post();
                        echo '<div class="l-content__flex">';
                        echo '<div class="l-news__date clay pink-d c-text__white">';
                        echo '<span class="l-news__y">' . get_the_date('Y.') . "</span>";
                        echo '<span class="l-news__md">' . get_the_date('n.j') . "</span>";
                        echo '</div>';
                        echo '<div class="l-news__content"><a href="' . get_the_permalink() . '">' . substr(get_the_content(), 0, 65) . ((strlen(get_the_content()) > 65) ? '...' : '') . '</a></div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <?php $morelink = get_post_type_archive_link($posttype); ?>
            <div class="l-content__footer c-button__xxx">
                <a href="<?php echo $morelink; ?>" class="l-content__footerbtn clay card pink-d">もっとみる
                </a>
            </div>
        </div>
<?php
    }
    wp_reset_postdata();
}

function show_instagram(){
    ?>
    <div class="l-content__wrapper l-content__col-lawn">
        <!-- content title -->
        <?php lb_draw_content_title('Instagram', ''); ?>

        <!-- content -->
        <div class="l-content__wrapper">
        <?php echo do_shortcode( '[instagram-feed feed=1]' ); ?>
        </div>
    </div>
    <?php
}

// カートに入れるボタンのフォーム書き換え用フィルター
function my_filter_single_item_inform($html)
{
    //処理
    // echo "====";
    wp_die($html);
    // echo "==x==";
    return $html;
}
add_filter('usces_filter_single_item_inform',  'my_filter_single_item_inform');

// 数量入力フォームの書き換え用フィルター
function lb_filter_the_itemQuant($html, $post)
{
    $min = 0;
    $max = usces_the_itemZaikoNum('return');
    $html = str_replace('type="text"', 'type="number"', $html);
    $html = preg_replace('/\/>/', ' data-min="' . $min . '" data-max="' . $max . '" />', $html);
    preg_match('/ id="([^"]+)" /', $html, $matches);
    $btnId = $matches[1];
    $btnIncqty = '<input type="button" value="＋" class="btnspinner" data-cal="1" targetId="' . $btnId . '">';
    $btnDecqty = '<input type="button" value="－" class="btnspinner" data-cal="-1" targetId="' . $btnId . '">';
    $html .= ($btnIncqty . $btnDecqty);
    
    wp_enqueue_script('jquery-js', 'https://little-brownies.com/testdesu/wp-content/themes/welcart_basic-child/js/spinner.js');
    return $html;
}
add_filter('usces_filter_the_itemQuant',  'lb_filter_the_itemQuant', 10, 2);

// 消費税タグを書き換える
function lb_filter_tax_guid( $str, $tax_rate ) {
    $str = str_replace('em', 'div', $str);
    return $str;
}
add_filter( 'usces_filter_tax_guid', 'lb_filter_tax_guid', 30, 2 );

/* アーカイブページタイトルの余計な文字を削除 */
add_filter('get_the_archive_title', 'lb_remove_archive_titleprefix', 30);
function lb_remove_archive_titleprefix($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_date()) {
        $title = get_the_time('Y年n月');
    } elseif (is_search()) {
        $title = '検索結果：' . esc_html(get_search_query(false));
    } elseif (is_404()) {
        $title = '「404」ページが見つかりません';
    } else {
    }
    return $title;
}

/**
 * Filter the upload size limit for administrators.
 *
 * @param string $size Upload size limit (in bytes).
 * @return int (maybe) Filtered size limit.
 */
function filter_site_upload_size_limit($size)
{
    // Set the upload size limit to 10 MB for users lacking the 'manage_options' capability.
    if (current_user_can('manage_options')) {
        // 10 MB.
        $size = 1024 * 1000 * 1000;
    }
    return $size;
}
add_filter('upload_size_limit', 'filter_site_upload_size_limit', 20);