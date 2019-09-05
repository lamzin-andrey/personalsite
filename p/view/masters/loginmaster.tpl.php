<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="Windows-1251">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="apptoken" content="<?php echo $app->token?>">

  <title>SB Admin 2 - Login</title>

  <!-- Custom fonts for this template-->
  <link href="/p/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/p/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="/s/aauth.css" rel="stylesheet">
  <link href="/p/.css" rel="stylesheet">
	<script src="/j/landcacherswinstaller.js"></script>
	<script>window.sReferer = "<?php echo o($app, 'referer') ?>";</script>
</head>

<body class="bg-gradient-primary">

  <div class="container" id="wrapperapp">
<transition appear name="fade">
<div  v-if="show">
	<!-- Outer Row -->
	<div class="row justify-content-center">

		<div class="col-xl-10 col-lg-12 col-md-9">

			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
			
					<!-- Nested Row within Card Body -->
					<div class="row">
					  <div class="col-lg-6 d-none d-lg-block <?php echo $route->app->sFormBgImageCss; ?>"></div>
					  <div class="col-lg-6">
						<div class="p-5">
						  <div class="text-center">
							<h1 class="h4 text-gray-900 mb-4"><?php echo l($route->app->formHeading)?></h1>
							<?php if ($route->app->isResetform): ?>
								<p class="mb-4 d-none d-sm-block"><?php echo l('forgotPasswordSmallText'); ?></p>
							<?php endif ?>
						  </div>
						<?php include $route->view;?>
						  <hr>
						  <?php if (!$route->app->isResetform):?>
							  <div class="text-center">
								<a class="small" href="/p/reset/"><?php echo l('Forgot Password'); ?>?</a>
							  </div>
						  <?php endif ?>
							<?php if ($route->app->isAuthform || $route->app->isResetform):?>
							  <div class="text-center">
								<a class="small" href="/p/signup/"><?php echo l('signup'); ?></a>
							  </div>
							<?php endif ?>
							
							<?php if (!$route->app->isAuthform):?>
							  <div class="text-center">
								<a class="small" href="/p/signin/"><?php echo l('gotoLoginPageLink'); ?></a>
							  </div>
							<?php endif ?>
						</div>
					  </div>
					</div>
            
				</div>
			</div>
		</div>
	</div>
</div>
</transition>
</div>
  
  <div class="footer bg-dark text-light">
			<div class="container">
				<div class="row">
					<div class="col">
						<span>Copyright &copy; andryuxa.ru 2019. Admin panel free template from <a href="https://startbootstrap.com/previews/sb-admin-2/" target="_blank">SB Admin-2</a></span>
					</div>
				</div>
			</div>
	</div>

  <!-- Bootstrap core JavaScript-->
  <script charset="UTF-8" src="/j/a.js"></script>
  <script src="/p/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/p/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/p/sbadmin2/js/sb-admin-2.min.js"></script>
  
  
<link rel="stylesheet" type="text/css" href="/s/bootstrap4_sticky_footer.css">
</body>

</html>
