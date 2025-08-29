document.addEventListener(
	"DOMContentLoaded", async () => {

		
		// remove song-2 (blur) class from banner-content on page load
		setTimeout(() => {
			const bannerContent = document.querySelector('.banner-content.song-2');
			if (bannerContent) {
				bannerContent.classList.remove('song-2');
			} 
		}, 200);

        //initialise Choices for select elements
        if(typeof Choices != 'undefined') {

            // Change placeholder text for phone prefix select to "Prefix"
            const phonePrefixSelect = document.querySelector('.phone-wrapper [data-name="phone-prefix"] select, .phone-wrapper [data-name="prone-prefix"] select');
            if (phonePrefixSelect) {
                const placeholderOpt =
                    phonePrefixSelect.querySelector('option[value=""], option[disabled][selected], option[disabled][value=""]')
                    || phonePrefixSelect.options[0];
                if (placeholderOpt) {
                    placeholderOpt.textContent = 'Prefix';
                }
            }
            
            const select_elements = document.querySelectorAll('select');

            //apply Choices to all select elements
            select_elements?.forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search...',
                });
            });

        }

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
					centeredSlides: true,
					breakpoints: {
						768: {
							centeredSlides: false
						}
					}
				});
			});

			// Initialize Infinite Marquee for locations
				new InfiniteMarquee({
					element: '.marquee-container',
					speed: 25000,
					smoothEdges: false,
					direction: 'right',
					gap: '10px',
					duplicateCount: -1,
					duplicateInnerElements: false,
					mobileSettings: {
						direction: 'top',
						speed: 20000
					},
					on: {
						beforeInit: () => {
							console.log('Not Yet Initialized');
						},

						afterInit: () => {
							console.log('Initialized');
						}
					}
				});
		}

			// Key features: toggle show more/less
			const featureToggles = document.querySelectorAll('.key-features-toggle');
			featureToggles.forEach(btn => {
				btn.addEventListener('click', () => {
					const listId = btn.getAttribute('aria-controls');
					const list = document.getElementById(listId);
					if (!list) return;
					const expanded = list.getAttribute('data-expanded') === 'true';
					list.setAttribute('data-expanded', expanded ? 'false' : 'true');
					btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
					const more = btn.getAttribute('data-more') || 'Show more';
					const less = btn.getAttribute('data-less') || 'Show less';
					btn.textContent = expanded ? more : less;
				});
			});

			const openBtn = document.getElementById('open-filter-modal');
			const modal = document.getElementById('filter-modal');

			if (openBtn && modal) {
					openBtn.onclick = () => {
					modal.classList.add('active');
				};

				modal.onclick = (e) => {
				if (e.target === modal) modal.classList.remove('active');
				};
			}
	});