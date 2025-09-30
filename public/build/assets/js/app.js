$(function() {
	"use strict";
	new PerfectScrollbar(".app-container"),
	new PerfectScrollbar(".header-message-list"),
	new PerfectScrollbar(".header-notifications-list"),


	    $(".mobile-search-icon").on("click", function() {
			$(".search-bar").addClass("full-search-bar")
		}),

		$(".search-close").on("click", function() {
			$(".search-bar").removeClass("full-search-bar")
		}),

		$(".mobile-toggle-menu").on("click", function() {
			$(".wrapper").addClass("toggled")
		}),
		



		$(".dark-mode").on("click", function() {

			if($(".dark-mode-icon i").attr("class") == 'bx bx-sun') {
				$(".dark-mode-icon i").attr("class", "bx bx-moon");
				$("html").attr("class", "light-theme");
				localStorage.setItem('theme', 'light-theme');
			} else {
				$(".dark-mode-icon i").attr("class", "bx bx-sun");
				$("html").attr("class", "dark-theme");
				localStorage.setItem('theme', 'dark-theme');
			}

		}), 

		
		$(".toggle-icon").click(function() {
			$(".wrapper").hasClass("toggled") ? ($(".wrapper").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($(".wrapper").addClass("toggled"), $(".sidebar-wrapper").hover(function() {
				$(".wrapper").addClass("sidebar-hovered")
			}, function() {
				$(".wrapper").removeClass("sidebar-hovered")
			}))
		}),
		$(document).ready(function() {
			$(window).on("scroll", function() {
				$(this).scrollTop() > 300 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut()
			}), $(".back-to-top").on("click", function() {
				return $("html, body").animate({
					scrollTop: 0
				}, 600), !1
			})
		}),
		
		$(function() {
			for (var e = window.location, o = $(".metismenu li a").filter(function() {
					return this.href == e
				}).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
		}),
		
		
		$(function() {
			$("#menu").metisMenu()
		}), 
		
		$(".chat-toggle-btn").on("click", function() {
			$(".chat-wrapper").toggleClass("chat-toggled")
		}), $(".chat-toggle-btn-mobile").on("click", function() {
			$(".chat-wrapper").removeClass("chat-toggled")
		}),


		$(".email-toggle-btn").on("click", function() {
			$(".email-wrapper").toggleClass("email-toggled")
		}), $(".email-toggle-btn-mobile").on("click", function() {
			$(".email-wrapper").removeClass("email-toggled")
		}), $(".compose-mail-btn").on("click", function() {
			$(".compose-mail-popup").show()
		}), $(".compose-mail-close").on("click", function() {
			$(".compose-mail-popup").hide()
		}), 
		
		
		$(".switcher-btn").on("click", function() {
			$(".switcher-wrapper").toggleClass("switcher-toggled")
		}), $(".close-switcher").on("click", function() {
			$(".switcher-wrapper").removeClass("switcher-toggled");
		}), $("#lightmode").on("click", function() {
			$("html").attr("class", "light-theme");
			localStorage.setItem('theme', 'light-theme');
			localStorage.removeItem('sidebarColor');
			localStorage.removeItem('headerColor');
		}), $("#darkmode").on("click", function() {
			$("html").attr("class", "dark-theme");
			localStorage.removeItem('sidebarColor');
			localStorage.removeItem('headerColor');
			localStorage.setItem('theme', 'dark-theme');
		}), $("#semidark").on("click", function() {
			$("html").attr("class", "semi-dark");
			localStorage.removeItem('sidebarColor');
			localStorage.removeItem('headerColor');
			localStorage.setItem('theme', 'semi-dark');
		}), $("#minimaltheme").on("click", function() {
			$("html").attr("class", "minimal-theme");
			localStorage.removeItem('sidebarColor');
			localStorage.removeItem('headerColor');
			localStorage.setItem('theme', 'minimal-theme');
		}), 
		
		$("#headercolor1").on("click", function() {
			// Remove all header color classes and add the selected one
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor1");
			localStorage.setItem('headerColor', 'headercolor1');
		}), $("#headercolor2").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor2");
			localStorage.setItem('headerColor', 'headercolor2');
		}), $("#headercolor3").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor3");
			localStorage.setItem('headerColor', 'headercolor3');
		}), $("#headercolor4").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor4");
			localStorage.setItem('headerColor', 'headercolor4');
		}), $("#headercolor5").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor5");
			localStorage.setItem('headerColor', 'headercolor5');
		}), $("#headercolor6").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor6");
			localStorage.setItem('headerColor', 'headercolor6');
		}), $("#headercolor7").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor7");
			localStorage.setItem('headerColor', 'headercolor7');
		}), $("#headercolor8").on("click", function() {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header headercolor8");
			localStorage.setItem('headerColor', 'headercolor8');
		})
		
	// sidebar colors 
	$('#sidebarcolor1').click(function() {
		theme1();
		localStorage.setItem('sidebarColor', 'sidebarcolor1');
	});
	$('#sidebarcolor2').click(function() {
		theme2();
		localStorage.setItem('sidebarColor', 'sidebarcolor2');
	});
	$('#sidebarcolor3').click(function() {
		theme3();
		localStorage.setItem('sidebarColor', 'sidebarcolor3');
	});
	$('#sidebarcolor4').click(function() {
		theme4();
		localStorage.setItem('sidebarColor', 'sidebarcolor4');
	});
	$('#sidebarcolor5').click(function() {
		theme5();
		localStorage.setItem('sidebarColor', 'sidebarcolor5');
	});
	$('#sidebarcolor6').click(function() {
		theme6();
		localStorage.setItem('sidebarColor', 'sidebarcolor6');
	});
	$('#sidebarcolor7').click(function() {
		theme7();
		localStorage.setItem('sidebarColor', 'sidebarcolor7');
	});
	$('#sidebarcolor8').click(function() {
		theme8();
		localStorage.setItem('sidebarColor', 'sidebarcolor8');
	});

	// Apply saved theme settings on page load
	$(document).ready(function() {
		var savedTheme = localStorage.getItem('theme');
		if (savedTheme) {
			$("html").removeClass("light-theme dark-theme semi-dark minimal-theme");
			$("html").addClass(savedTheme);
		}

		var savedHeaderColor = localStorage.getItem('headerColor');
		if (savedHeaderColor) {
			$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
			$("html").addClass("color-header " + savedHeaderColor);
		}

		var savedSidebarColor = localStorage.getItem('sidebarColor');
		if (savedSidebarColor) {
			$("html").removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
			$("html").addClass("color-sidebar " + savedSidebarColor);
		}
	});


	function theme1() {
		// Remove existing sidebar color classes and add the selected one
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor1');
	}

	function theme2() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor2');
	}

	function theme3() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor3');
	}

	function theme4() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor4');
	}

	function theme5() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor5');
	}

	function theme6() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor6');
	}

	function theme7() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor7');
	}

	function theme8() {
		$('html').removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
		$('html').addClass('color-sidebar sidebarcolor8');
	}
	
	
});