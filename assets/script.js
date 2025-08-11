document.addEventListener(
	"DOMContentLoaded", async () => {

		// //mmenu slideout menu navigation
		// const slideout_menu = document.querySelector( "#generate-slideout-menu .inside-navigation" );
		
		// //initialise MmenuLight for mobile menu & if has children items to create menu
		// if(typeof MmenuLight != 'undefined') {
				
		// 	const menu = new MmenuLight(slideout_menu),
		// 		  menu_navigation = menu.navigation(),
		// 		  drawer = menu.offcanvas();

		// 	document.querySelector( '.menu-toggle' )
		// 		.addEventListener(
		// 			"click", ( evnt ) => {
		// 				evnt.preventDefault();
		// 				drawer.open();
		// 			}
		// 		);
			
		// }
		
		// remove song-2 (blur) class from banner-content on page load
		setTimeout(() => {
			const bannerContent = document.querySelector('.banner-content.song-2');
			if (bannerContent) {
				bannerContent.classList.remove('song-2');
			} 
		}, 1000);


		// close all details elements on click
		const details_elements = document.querySelectorAll('details');

		if(details_elements) {

			details_elements.forEach((acc) => {

				acc.addEventListener("click", (event) => {
					
				  // Stop the event from bubbling up
				  event.stopPropagation();
					
				  // Close all the details that are not clicked
				  details.forEach((detail) => {
									  
					if (detail !== acc) {
					  detail.removeAttribute("open");
					}

				  });

				});

			  });

		}
		


		//nice select2 vanilla js 
		if(typeof NiceSelect != 'undefined') {

			var all_select_instances = [];
			let select_el = document.querySelectorAll('select');

			for (let i = 0; i < select_el.length; i++) {         
				var nice_select_instance = NiceSelect.bind(document.querySelectorAll("select")[i]);
				all_select_instances.push(nice_select_instance);
			}

			reset_niceselect = function() {

				for (let i = 0; i < all_select_instances.length; i++) {
					all_select_instances[i].update();
				}

			}
			
			//membership tabs
			

		}


		//readmore functionality
		if(typeof readSmore != "undefined") {

			//content read more 
			const readmore_content = document.querySelectorAll('.read-more-content');
			const options = {
				blockClassName: 'read-more',
				moreText: 'Read more',
				lessText: 'Read less',
				wordsCount: 135,
				isInline: true,
			}
			readSmore(readmore_content, options).init();  
			
		}

		
		//initialise micromodal for popup forms
		if(typeof MicroModal != 'undefined') {
			
			MicroModal.init({
				disableScroll: true,
			});
			
		}

		//add smooth scroll for all anchor links except those with class 'popup-button'
		document.querySelectorAll('a[href^="#"]').forEach(element => {
			element.classList.add('smooth-scroll');
		});
		
		// initialize locations slider swiper
		if ( typeof Swiper !== 'undefined' ) {
			new Swiper( '.locations-slider .swiper-container', {
				slidesPerView: 'auto',
				spaceBetween: 20,
				loop: true,
				freeMode: true,
				freeModeMomentum: false,
				autoplay: {
					delay: 0,
					disableOnInteraction: false,
				},
				speed: 4000,
			} );
		}

		// Content image slider: images slide, content fades
		if ( typeof Swiper !== 'undefined' ) {
			document.querySelectorAll('.content-image-slider').forEach(section => {
				const container = section.querySelector('.swiper-container');
				const nextEl = section.querySelector('.swiper-button-next');
				const prevEl = section.querySelector('.swiper-button-prev');
				const contents = Array.from(section.querySelectorAll('.content-stack .content-item'));
				if (!container || contents.length === 0) return;

				const activate = (index) => {
					contents.forEach((el, i) => {
						if (i === index) {
							el.classList.add('is-active');
							el.setAttribute('aria-hidden', 'false');
						} else {
							el.classList.remove('is-active');
							el.setAttribute('aria-hidden', 'true');
						}
					});
				};

				activate(0);

				const swiper = new Swiper(container, {
					slidesPerView: 1,
					loop: contents.length > 1,
					speed: 800,
					navigation: { nextEl, prevEl },
					effect: 'slide',
					on: {
						slideChange() {
							activate(this.realIndex);
						}
					}
				});
			});
		}
	}
)