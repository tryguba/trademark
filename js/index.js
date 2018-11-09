$(document).ready(function () {
	var main = function () {
		$('.slidin').click(function () {
			$('.nav-screen').animate({right: "0px"}, 200);
			$('body').animate({right: "305px"}, 200);
		});
		$('.close').click(function () {
			$('.nav-screen').animate({right: "-305px"}, 200);
			$('body').animate({right: "0px"}, 200);
		});
		$('.nav-links a').click(function () {
			$('.nav-screen').animate({right: "-305px"}, 500);
			$('body').animate({right: "0px"}, 500);
		});
	};
	
	$("#form").submit(function () {
		let msg = $('#form').serialize();
		$.ajax({
			type: "POST",
			url: "mail.php",
			data: msg,
			success: function (e) {
				// alert(e);
				console.log(e);
				swal({
					type: 'success',
					text: e
				})
			},
			error: function (err) {
				alert(err);
			}
		}).done(function () {
			$(this).find("input").val("");
			$("#form").trigger("reset");
			$('.nav-screen').animate({right: "-305px"}, 200);
			$('body').animate({right: "0px"}, 200);
		});
		return false;
	});
	$(document).ready(main);
});
// ================================= send form ==================================
