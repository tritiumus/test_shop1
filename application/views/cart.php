	<style>
		.row_cart {
			//border: solid 1px blue;
			margin: 10px;
		}
		.row_prod {
			border: solid 1px green;
		}
		
		.row_prod div {
			display: inline;
		}
		
	</style>
</head>
<body>
	<div class="content">
		<div class="row">
			<div class="col-md-5 col-md-offset-2 banner">
				<p>Banner</p>
			</div>
			<div class="col-md-2 col-md-offset-1 cart">
				<button type="button" id="catalog_butt" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span></button>
			</div>
		</div>
		<div class="row row_cart">
			<div class="col-md-8 col-md-offset-2" id="col-cart">
				<?php
					if(isset($flag_cart) && $flag_cart == 'empty') {
						echo $full_price;
					} else {
						echo '<div class="row">';
						echo '<div class="col-md-7">Наименование</div>';
						echo '<div class="col-md-1">Цена</div>';
						
						echo '<div class="col-md-2">Количество</div>';
						
						echo '<div class="col-md-2">Cтоимость</div>';
						echo '</div>';
						foreach($arr_cart as $row) {
							echo '<div class="row row_prod">';
							echo '<div class="col-md-7">'.$row['name'].'</div>';
							echo '<div class="col-md-1">'.$row['price'].'</div>';
							
							echo '<div class="col-md-2">'.$row['count'].'</div>';
							
							echo '<div class="col-md-2">'.$row['price']*$row['count'].'</div>';
							echo '</div>';
						}
						echo '<div class="row row_price"><div class="col-md-4 col-md-offset-8">Общая стоимость: ';
						echo $full_price;
						echo '</div>';
					}
				
					if(!isset($flag_cart)) {
						echo '<div class="row">
							<div class="col-md-2">
								<button type="button" id="order_butt" class="btn btn-primary">Заказать</button>
							</div>
							<div class="col-md-2">
								<button type="button" id="clear_butt" class="btn btn-primary">Очистить</button>
							</div>
						</div>';
					}
				?>
			</div>
		</div>
	</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		
		$("#catalog_butt").click(function() {
			document.location.href = '<?= $site_url; ?>/Catalog/index';
		});
		
		$("#clear_butt").click(function() {
			$.post('<?= $site_url; ?>/Cart/clear_cart');
			setTimeout(function() { document.location.href = '<?= $site_url; ?>/Catalog/index'; }, 500);
			
		});
		
		$("#order_butt").click(function() {
			$.getJSON('<?= $site_url; ?>/Cart/send_order', function(data) {
				if(data.state == "success") {
					$("#col-cart").html(data.mess);
				} else {
					alert(data.mess);
				}
			});
		});
	});
	</script>
</body>
</html>
