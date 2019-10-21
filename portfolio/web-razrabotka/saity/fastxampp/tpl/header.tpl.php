<table cellspacing="0" cellpadding="0" class="header-wr"><tbody><tr>
	<td class="logoname lef w50" style="background:linear-gradient(to right, #d0d0e9, #5555AA)"><a href="<?=WEBROOT . ($currlang == 'en/' ? '/en/' : '')?>" >
		<div class="fasttext">FAST</div>
		<div class="fastimg">
				<div class="left"><img src="<?=img('xampp.png')?>"></div>
  		<div style="clear:both"></div>
				<div class="left mtop1"> XAMPP </div><div class="endfloat"></div>
		</div></a>
	</td>
	<td class="logoname lef w50" style="background-color: rgb(85, 85, 170);">
		<div style="margin-left: 20px; font-size: 14pt; background-color: rgb(85, 85, 170); color: rgb(255, 255, 255); padding: 1px; width: 209px; text-align: center;">
			<p>PHP 7.0.[4/8].0 </p>
			<p><span style="margin-left:61px" class="hbit note b">32 / 64 bit</span></p>
		</div>
	</td>
	<td class="hmenu w50" style="background:linear-gradient(to right, #5555AA,  #F0F0F0)">
		<table class="w100p">
			<tbody><tr>
				<td class="osu ">
					<a href="<?=WEBROOT?>/ubuntu/<?=$currlang?>">
						<div class="osuimg h32">
								<div><img width="40" src="<?=img('ubuntu48.png')?>"></div>
						</div>
						<div class="osutext ubuntu m20">Ubuntu</div>
					</a>
				</td>
				
				<td class="xu ">
					<a href="<?=WEBROOT?>/xubuntu/<?=$currlang?>">
						<div class="osxuimg h56">
								<div><img width="47" src="<?=img('xubuntu48.png')?>"></div>
						</div>
						<div class="osxutext xubuntu">Xubuntu</div>
					</a>
				</td>
				
				<td class="ksu ">
					<a href="<?=WEBROOT?>/kubuntu/<?=$currlang?>">
						<div class="oskuimg h32">
								<div><img width="40" src="<?=img('kubuntu48.png')?>"></div>
						</div>
						<div class="oskutext kubuntu m20">Kubuntu</div>
					</a>
				</td>
				
				<td class="osmi">
					<a href="<?=WEBROOT?>/mint/<?=$currlang?>">
						<div class="osmimg h32">
								<div><img src="<?=img('mint48.png')?>"></div>
						</div>
						<div class="osmitext mint m20">Mint</div>
					</a>
				</td>
			</tr>
		</tbody></table>
	</td>
	</tr>
	<tr>
		<td style="text-align:center" colspan="3">
			<style>
				.av1000{
					background: linear-gradient(to bottom,white, white,white,white,white,#ccc);
				}
			</style>
			<?php if(!isset($ignoreCordovaBanner)): ?>
			<div class="elfs">
				<img src="<?=img('elfs/7.png')?>"> 
				<img src="<?=img('elfs/6.png')?>"> 
				<img src="<?=img('elfs/5.png')?>"> 
				<img src="<?=img('elfs/4.png')?>"> 
				<img src="<?=img('elfs/3.png')?>"> 
				<img src="<?=img('elfs/2.png')?>"> 
				<img src="<?=img('elfs/1.png')?>"> 
			</div>
			<?php else: ?>
			<div class="av1000">
				<img src="<?=img('acord/al.png')?>"> <img src="<?=img('acord/icon.png')?>"> <img src="<?=img('acord/ar.png')?>">
			</div>
			<?php endif ?>
			
		</td>
	</tr>
</tbody></table>
