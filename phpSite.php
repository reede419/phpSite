<?php
if (!defined('READFILE')) {
	exit ("Error, wrong way to file.<br><a href=\"/\">Go to main</a>.");
}
$db = get_db_connection();
// print_var($_GET);

if ( !empty( $_GET ) && $_GET['offset'] && $_GET['count'] ) {
	$result = $db->query("SELECT * FROM pricelist  LIMIT ". $_GET['count'] ." OFFSET ". $_GET['offset']);
	$step = (($_GET['offset'] / $_GET['count'])) ;
} else {
	$result = $db->query("SELECT * FROM pricelist LIMIT 20 OFFSET 0");
	$step = 0;
}


$a = $db->query("SELECT COUNT(*) FROM pricelist");
$b = $a->fetch_assoc();
$count_posts = ceil($b['COUNT(*)']);
$count_pages = ceil(($b['COUNT(*)'])/20);
$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
$url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$array = explode( '?', $url );
$page = $array[0];
// $product = $result->fetch_assoc($sql);

?>
<main>
	<div class="container">
		<?php require_once(dirname(__FILE__).'/nav-container.php'); ?>
	</div>
	<div class="all-pages">
		<section data-p='1'>
			<?php
			echo '<div class="goods-table">';
			echo '<div class="table-goods">'; 
			?>
			<div class="thead">
				<span>Модель</span>
				<span>Артикль</span>
				<span>Названия</span>
				<span>Описание</span>
				<span>Цена</span>
				<span>Кол-во</span>
				<span>Бренд</span>
				<span>Изображения</span>						
			</div>
			<?php
			while($row = $result->fetch_assoc()) {
				$response_result = '<div class="row-goods">';
				$response_result .= '<div class="row-model" data-id=" '. $row['id']. ' "><p>'. $row['model'] . '</p></div>';
				$response_result .= '<div class="row-article" data-id=" '. $row['id']. ' "><p>'. $row['article'] . '</p></div>';
				$response_result .= '<div class="row-item-name" data-id=" '. $row['id']. ' "><p>'. $row['item_name'] . '</p></div>';
				$response_result .= '<div class="row-description" data-id=" '. $row['id']. ' "><p>'. $row['description'] . '</p></div>';
				$response_result .= '<div class="row-price" data-id=" '. $row['id']. ' "><p>'. $row['price'] . '</p></div>';
				$response_result .= '<div class="row-measure" data-id=" '. $row['id']. ' "><p>'. $row['measure'] . '</p></div>';
				$response_result .= '<div class="row-brand" data-id=" '. $row['id']. ' "><p>'. $row['brand'] . '</p></div>';
				$response_result .= '<div class="row-image" data-id=" '. $row['id']. ' ">'. ((!empty($row['picture'])) ? '<img src="http://conf.homesystems.com.ua/exchange/import/'. $row['picture'].'">' : '' ) . '</div>';
				// $response_result .= '<div class="row-image" data-id=" '. $row['id']. ' ">'. ((!empty($row['picture'])) ? '<img src="http://conf/exchange/import/'. $row['picture'].'">' : '' ) . '</div>';
				$response_result .= '<div class="row-button"><button type="button" class="delete remove-item" data-id=" '. $row['id']. ' ">Удалить</button><button type="button" class="edit-item open-modal-btn" data-id=" '. $row['id']. ' ">Редактировать</button></div>';
				// $response_result .='<td><a href='?del_id={$row['id']}'>Удалить</a></td>'
				$response_result .= '</div>';
				echo $response_result;
			}
			echo '</div>';
			echo '</div>';
			//print_var($_POST[ 'model' ], $_POST[ 'article' ], $_POST[ 'item_name' ]);
			?>

<!-- ************edit items **************-->
			<div id="wrapper">
				<div class="cover"></div>
				<div class="modal">
					<div class="content_edit">
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" class="svg-inline--fa fa-times svg fa-w-11 fa-2x"><path fill="orange" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" class=""></path></svg>
						<p>Редактировать элемент</p>
						<form onsubmit="return false" class="update_row">
							<label for="model" class="model"><span>Модель</span></label>
							<input type="text" name="model" value="" id="model">
							<label for="article" class="article"><span>Артикль</span></label>
							<input type="text" name="article" value="" id="article">
							<label for="item_name" class="item_name"><span>Названия</span></label>
							<input type="text" name="article" value="" id="item_name">
							<label for="description" class="description"><span>Описание</span></label>
							<textarea type="text" name="description" value="" id="description"></textarea>
							<label for="price" class="price"><span>Цена</span></label>
							<input type="number" name="price" value="" id="price" step="0.01" placeholder="0.00">
							<label for="measure" class="measure"><span>Количество</span></label>
							<input type="text" name="measure" value="" id="measure">
							<label for="brand" class="brand"><span>Бренд</span></label>
							<input type="text" name="brand" value="" id="brand">
							<label for="picture" class="picture"><span>Изображения</span></label>
							<div class="div_picture"><img src="" id="picture"></img></div>
							<div id="file-upload">
							<label class="add-picture-label" for="add_picture">
							<input type="file" name="file" class="field" value="" id="add_picture">
							<span>Выберите изображение</span>
						  </label>
							</div>
							<!-- <input type="file" name="picture" class="field" value="" id="add_picture"> -->
							<button type="button" data-id="<?php echo $row['id']; ?>" class="refresh_item">Обновить</button>
						</form>
					</div>
				</div>
			</div>

			<!--*************** add new items **************** -->
			<div id="wrapper_add">
				<div class="cover_add"></div>
				<div class="modal_add">
					<div class="content_add">
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" class="svg-inline--fa fa-times svg_close fa-w-11 fa-2x"><path fill="orange" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" class=""></path></svg>
						<p>Добавить элемент</p>
						<form onsubmit="return false" class="add_row">
							<label for="model" class="model"><span>Модель</span></label>
							<input type="text" name="model" class="field" value="" id="add_model" required>
							<label for="article" class="article"><span>Артикль</span></label>
							<input type="text" name="article" class="field" value="" id="add_article" required>
							<label for="item_name" class="item_name"><span>Названия</span></label>
							<input type="text" name="article" class="field" value="" id="add_item_name" required="">
							<label for="description" class="description"><span>Описание</span></label>
							<textarea type="text" name="description" class="field" value="" id="add_description" required=""></textarea>
							<label for="price" class="price"><span>Цена</span></label>
							<input type="text" name="price" class="field" value="" id="add_price" required="">
							<label for="measure" class="measure"><span>Количество</span></label>
							<input type="text" name="measure" class="field" value="" id="add_measure" required="">
							<label for="brand" class="brand"><span>Бренд</span></label>
							<input type="text" name="brand" class="field" value="" id="add_brand" required="">
							<label for="picture" class="picture"><span>Изображения</span></label>
							<!-- <div class="div_picture"><img src="" id="picture"></img></div> -->
							<input type="file" name="picture" class="field" value="" id="addd_picture">
							<!-- <label for="picture" class="picture"><span>Изображения</span></label>
							<input type="file" name="picture" class="field" value="" id="add_picture"> -->
							<button type="button" data-id="<?php echo $row['id']; ?>" class="add_item">Добавить</button>
						</form>
					</div>
				</div>
			</div>
			<button id="add_elem">Добавить элементы</button>
<?php
showCard('morePricelist', ['posts' => $count_posts, 'pages' => $count_pages, 'step'=>$step, 'url'=>$page, ]); ?>
		</section>
	</div>
</main>
<script src="/js/admin-goods.js"></script>