<?php $lang=$_REQUEST['lang']; ?>
<!DOCTYPE HTML>
<!--
	Introspect by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>
			<?php if($lang=='la'): ?>
				ວິທີໃຊ້ App Lao School
			<?php else: ?>
			<?php endif; ?>
		</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="index.html" class="logo">
					<?php if($lang=='la'): ?>
						ວິທີໃຊ້ App Lao School
					<?php else: ?>
					<?php endif; ?>
					</a>
					<nav id="nav">
						
					</nav>
				</div>
			</header>
			<a href="#menu" class="navPanelToggle"><span class="fa fa-bars"></span></a>

		<!-- Main -->
			<section id="main">
				<div class="inner">
					<section>
						<h4>ສຳລັບຜູ້ປົກຄອງນັກຮຽນ</h4>
						
						<div>
							<ol>
								<li>ກົດເຂົ້າ  App Lao School.</li>
								<li>User name ຊືື່ຜູ້ ນຳໃຊ້ .</li>
								<li>Password ລະຫັດຜ່ານ.</li>
								<li>Login ເຂົ້າສູ່ລະບົບ.
									<ul>
										<li>ກໍລະນີລືມລະຫັດຜ່ານແມ່ນໃຫ້ກົດໃຊ່ບ່ອນ "ລືມລະຫັດຜ່ານ (forgot password)"
											<ul>
												<li>User name ຊື່ຜູ້ນໍາໃຊ້</li>
												<li>Phone number ເບີໂທລະສັບ</li>
											</ul>
										</li>
										<li>ແລ້ວຈະມີຂໍ້ຄວາມເຂົ້າທາງ SMS ເພື່ອຢືນຢັນລະຫັດໃຫມ່</li>
									</ul>
								</li>
							</ol>
						</div>
							
						<div>
							<ul>
								<li>ເຂົ້າຊ້ອຍເລືອກ "ຂໍ້ຄວາມ (Massages)", "ປະກາດ (Announcement)", "ການມາຮຽນ(Attendance)", "ຄະແນນ (Scores)", "ອື່ນໆ (More)"</li>
								<li>ຄິກເຂົ້າ ’’ຂໍ້ຄວາມ’’ ເພື່່ອອ່ານຂໍ້ຄວາມ, ສົ່ງຂໍ້ຄວາມ (ຫາກຕ້ອງການສົ່ງໃຫ້ຕິກໃສ່ເຄື່ອງໝາຍການສົ່ງ ຢູ່ເບື້ອງຂວາສຸດຂອງໜ້າຈໍ ຂ້າງກັບ ’’ສົ່ງແລ້ວ/Sent’’ ພ້ອມເນື້ອໃນຈາກນັ້ນກົດສົ່ງ)</li>
								<li>ຄິກເຂົ້າ ‘’ປະກາດ’’ ເພື່ອເບິ່ງລາຍລະອຽດການແຈ້ງການຂອງໂຮງຮຽນ (ແຈ້ງພັກ,ແຈ້ງເກັບເງີນຕ່າງໆ...)</li>
								<li>ຄິກເຂົ້າ ‘’ການມາຮຽນ’’ ເພື່ອສົ່ງໃບລາພັກໃຫ້ນັກຮຽນ (ຫາກຕ້ອງການລາພັກໃຫ້ຕິກໃສເຄື່ອງໝາຍ + ‘’ບວກ’’ ເລືອກວັນທີ ທີ່ຈະລາພັກມື້ໃດ ຫາ ມື້ໃດ ພ້ອມຂຽນເຫດຜົນຈາກນັ້ນກົດສົ່ງ)</li>
								<li>ຄິກເຂົ້າ ‘’ຄະແນນ’’  ເພື່ອເບິ່ງຄະແນນແຕ່ລະວິຊາ, ປະຈໍາເດືອນ, ສະເລຍປະຈໍາເດືອນປະຈຳພາກຮຽນ ແລະ ການຈັດອັນດັບ</li>
								<li>ຄິກເຂົ້່າ ‘’ອື່ນໆ’’ ຈະມີຊ້ອຍໃຫ້ເລືອກ
									<ul>
										<li>ບັນທຶກຂອງໂຮງຮຽນ ( school records )</li>
										<li>-	ຕາຕະລາງຮຽນ ( timetable )</li>
									</ul>
								</li>
							</ul>
						</div>
					</section>
				</div>
			</section>

		<!-- Footer -->
			<section id="footer">
				<div class="inner">
					<header>
						<h2>Get in Touch</h2>
					</header>
					<form method="post" action="#">
						<div class="field half first">
							<label for="name">Name</label>
							<input type="text" name="name" id="name" />
						</div>
						<div class="field half">
							<label for="email">Email</label>
							<input type="text" name="email" id="email" />
						</div>
						<div class="field">
							<label for="message">Message</label>
							<textarea name="message" id="message" rows="6"></textarea>
						</div>
						<ul class="actions">
							<li><input type="submit" value="Send Message" class="alt" /></li>
						</ul>
					</form>
					<div class="copyright">
						
					</div>
				</div>
			</section>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>