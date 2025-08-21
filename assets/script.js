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

		// Content image slider - 2 sliders in one section
			document.querySelectorAll('.content-image-slider').forEach(section => {
				const imagesContainer = section.querySelector('.images-slider.swiper-container');
				const contentContainer = section.querySelector('.content-slider.swiper-container');
				const nextEl = section.querySelector('.content-image-slider .image-slider-next');
				const prevEl = section.querySelector('.content-image-slider .image-slider-prev');
				if (!imagesContainer || !contentContainer) return;

				// Content slider
				const contentSwiper = new Swiper(contentContainer, {
					slidesPerView: 1,
					effect: 'fade',
					fadeEffect: { crossFade: true },
					allowTouchMove: false,
				});

				// Image slider with fraction nav
				const imagesSwiper = new Swiper(imagesContainer, {
					slidesPerView: 1,
					loop: contentSwiper.slides.length > 1,
					speed: 600,
					spaceBetween: 20,
					navigation: { nextEl, prevEl },
					pagination: {
       						el: ".image-slider-pagination",
        					type: "fraction",
      						},
				});

				// Sync content fade to image slide
				imagesSwiper.on('slideChange', () => {
					contentSwiper.slideTo(imagesSwiper.realIndex);
				});
			});
			
			
			// Featured property slider
			document.querySelectorAll('.featured-property-slider').forEach(section => {
				const container = section.querySelector('.property-slider.swiper-container');
				const prevEl = section.querySelector('.property-slider .property-slider-prev');
				const nextEl = section.querySelector('.property-slider .property-slider-next');
				if (!container) return;

				new Swiper(container, {
					slidesPerView: 1.1,
					loop: true,
					speed: 800,
					spaceBetween: 20,
					navigation: { prevEl, nextEl },
					pagination: {
       						el: ".property-slider-pagination",
        					type: "fraction",
      						},
				});
			});
		}
		
	}
)