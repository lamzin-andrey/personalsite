<!-- mobile header  -->
<div class="row u-fastxampp-header-xs">
    <div class="col-6">
        <div class="u-fastlogo">
            <a href="<?=WEBROOT . (@$currlang == 'en/' ? '/en/' : '')?>" >
                <div class="text-white text-center">FAST</div>
                <div>
                    <img src="<?=img('xampp.png')?>">
                </div>
                <div class="text-white text-center">XAMPP</div>
            </a>
        </div>
    </div>
    <div class="col-6 align-items-center">
        <div>
            <div class="text-white u-mw-124px">PHP 7.4.1</div>
            <div class="u-text-orange">64 bit</div>
            <div class="u-">&nbsp;</div>
        </div>

    </div>
    <div class="col d-flex justify-content-end align-items-end ">
        <div class="mx-2 u-cell4">
            <a href="<?=WEBROOT?>/ubuntu/<?=$currlang?>">
                <div class="text-center">
                    <img class="u-menu-logo" src="<?=img('ubuntu48.png')?>">
                </div>
                <div class="u-text-orange text-center">
                    Ubuntu
                </div>
            </a>
        </div>
        <div class="mx-2 u-cell4">
            <a href="<?=WEBROOT?>/xubuntu/<?=$currlang?>">
                <div class="text-center">
                    <img src="<?=img('xubuntu48.png')?>">
                </div>
                <div class="text-center">
                    Xubuntu
                </div>
            </a>
        </div>
        <div class="mx-2 u-cell4">
            <a href="<?=WEBROOT?>/kubuntu/<?=$currlang?>">
                <div class="text-center">
                    <img class="u-ku-menu-logo" src="<?=img('kubuntu48.png')?>">
                </div>
                <div class="text-dark text-center">
                    Kubuntu
                </div>
            </a>
        </div>
        <div class="mx-2 u-cell4">
            <a href="<?=WEBROOT?>/mint/<?=$currlang?>">
                <div class="text-center">
                    <img class="u-mint-menu-logo" src="<?=img('mint48.png')?>">
                </div>
                <div class="text-success text-center">
                    Mint
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /mobile header  -->


<?php if(!isset($ignoreCordovaBanner)): ?>
    <!-- elephants  -->
    <div class="row bg-light">
        <div class="col pb-2">
			<div class="d-flex align-items-end justify-content-center">
				<div id="elfs">
					<img class="u-e7" src="<?=img('elfs/7.png')?>">
					<img class="u-e6" src="<?=img('elfs/6.png')?>">
					<img class="u-e5" src="<?=img('elfs/5.png')?>">
					<img class="u-e4" src="<?=img('elfs/4.png')?>">
					<img class="u-e3" src="<?=img('elfs/3.png')?>">
					<img class="u-e2" src="<?=img('elfs/2.png')?>">
					<img class="u-e1" src="<?=img('elfs/1.png')?>">
				</div>
			</div>
        </div>
    </div>
    <!-- /elephants  -->
<?php else: /* TODO apache cordova banner css layout */ ?>

    <!--div class="av1000">
        <img src="<?=img('acord/al.png')?>"> <img src="<?=img('acord/icon.png')?>"> <img src="<?=img('acord/ar.png')?>">
    </div-->

    <div class="row ">
        <div class="col bg-white py-4">
            <div class="d-flex justify-content-center align-items-center av1000">
                <div class="d-flex align-items-center justify-content-center u-acord-banner-w py-4 text-center">
                    <div class="u-acord-logo2">
                        <img src="<?=img('acord/al.png')?>"> <img src="<?=img('acord/icon.png')?>"> <img src="<?=img('acord/ar.png')?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /banner  -->
<?php endif ?>
