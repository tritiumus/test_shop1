<style>
		.banner {
			border: solid 1px yellow;
		}
		
		.cart {
			border: solid 1px red;
		}
		
		.prod {
			border: solid 1px green;
			
			//height: 150px;
		}
		
		.prod div {
			padding: 5px;
		}
		
		.row_prod {
			border: solid 1px blue;
		}
		.num, .current {
			display: inline;
			padding: 10px;
			
		}
		.current {
			font-weight: bold;
			color: white;
			background-color: green;
		}
	</style>
</head>

<body>
	<div class="content">
		<div class="row">
			<div class="col-md-5 col-md-offset-2 col-sm-5 col-sm-offset-1 banner">
				<p>Banner</p>
			</div>
			<div class="col-md-2 col-md-offset-1 col-sm-5 cart">
				<button type="button" id="cart_butt" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-shopping-cart"></span></button>
				<p id="full_price"><?= $full_price;?></p> <!--glyphicon glyphicon-list-alt glyphicon glyphicon-home-->
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
				<?php echo $pagination; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
				<div class="row row_prod">
					<?php
						foreach($catalog as $id_p => $prod) {
							echo '<div class="col-md-4 col-lg-3 prod" id="'.$id_p.'">';
							echo '<div class="row"><h3>'.$prod['name'].'</h3>';
							echo '<p>'.$prod['par1'].'</p>';
							echo '<p>'.$prod['par2'].'</p></div>';
							echo '<div class="row"><div class="col-md-7"><h4>Цена: '.$prod['price'].'</h4></div>';
							if(array_key_exists($id_p, $curr_cart)) {
								$count = $curr_cart[$id_p]['count'];
							} else {
								$count = 0;
							}
							echo '<div class="col-md-1">'.$count.'</div>';
							echo '<div class="col-md-4"><button type="button" class="btn btn-primary btn-xs pl-pr"><span class="glyphicon glyphicon-plus"></span></button>
							<button type="button" class="btn btn-primary btn-xs min-pr"><span class="glyphicon glyphicon-minus"></span></button></div>';
							echo '</div></div>';
						}
					?>
					<!--<div class="col-md-4 col-lg-3 prod">
						<p>product2</p>
					</div>-->
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$(".pl-pr").click(function() {
				var id_p = $(this).parent().parent().parent().attr('id');
				var counter_p =  $(this).parent().prev();
				var val_co = counter_p.text();
				val_co = ++val_co;
				counter_p.text(val_co);
				set_prod(id_p, val_co);
			});
			$(".min-pr").click(function() {
				var id_p = $(this).parent().parent().parent().attr('id');
				var counter_p = $(this).parent().prev();
				var val_co = counter_p.text();
				val_co = --val_co;
				if(val_co < 0) {
					val_co = 0;
				}
				counter_p.text(val_co);
				set_prod(id_p, val_co);
			});
			
			function set_prod(id_prod, co_prod) {
				var posting = $.post('<?= $site_url; ?>/Catalog/set_prod_in_cart', { id_p: id_prod, co_p: co_prod}, function(data) {
					$("#full_price").text(data);
				});
			}
			
			$("#cart_butt").click(function() {
				document.location.href = '<?= $site_url; ?>/Cart/index';
			});
		});
	</script>
</body>
</html>
