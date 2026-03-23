<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// First, collect all unique categories
$categories = array();
if( have_rows('faqs') ):
    while( have_rows('faqs') ): the_row();
        $category_value = get_sub_field('category');
        if( $category_value && !isset($categories[$category_value]) ):
            $field = get_sub_field_object('category');
            $category_label = isset($field['choices'][$category_value]) ? $field['choices'][$category_value] : $category_value;
            $categories[$category_value] = $category_label;
        endif;
    endwhile;
endif;
?>


<section>
    <div class="container">
        <div class="faqs">
            <div class="col-12 col-lg-3">
                <h2>Sectors</h2>
                <?php if( !empty($categories) ): ?>
                    <ul class="faq-categories">
                        <li><button class="faq-category-btn active" data-category="all">All<svg class="category-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button></li>
                        <?php 
                        foreach( $categories as $value => $label ): 
                        ?>
                            <li><button class="faq-category-btn" data-category="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?><svg class="category-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button></li>
                        <?php 
                        endforeach; 
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-12 col-lg-9">
                <div class="faq-section">
                    <?php
                    if( have_rows('faqs') ):
                        while( have_rows('faqs') ): the_row();
                            $question = get_sub_field('question');
                            $answer = get_sub_field('answer');
                            $category = get_sub_field('category');
                            ?>
                            <div class="faq-item" data-category="<?php echo esc_attr( $category ); ?>">
                                <h2 class="faq-question">
                                    <?php echo esc_html( $question ); ?>
                                    <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </h2>
                                <div class="faq-answer">
                                    <?php echo wp_kses_post( wpautop( $answer ) ); ?>
                                </div>
                            </div>
                            <?php
                        endwhile;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    .faqs {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 992px;
        margin: auto;
        gap: 40px;
    }

    @media (min-width: 992px) {
        .faqs {
            flex-direction: row;
        }

        .faqs .col-lg-3 {
            flex: 0 0 auto;
        }

        .faqs .col-lg-9 {
            flex: 1;
        }
    }

    @media (max-width: 991px) {
        .faqs .col-12:first-child {
            display: none;
        }
    }

    .faqs h2 {
        color: var(--body-text);
        font-size: 24px;
        font-style: normal;
        font-weight: 600;
        line-height: 36px;
        letter-spacing: -0.48px;
        text-transform: none;
        margin-bottom: 20px;
    }

    .faq-categories {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ul.faq-categories {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .faq-category-btn {
        color: #BFA570;
        font-family: Satoshi;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 18px; /* 150% */
        letter-spacing: 0.6px;
        text-transform: uppercase;
        background: none;
        padding: 0;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-arrow {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .faq-category-btn.active .category-arrow {
        opacity: 1;
    }

    .faqs button:is(:hover, :focus) {
        color: inherit;
        background-color: transparent;
    }

    .faq-item {
        margin-bottom: 24px;
        transition: opacity 0.3s ease;
    }

    .faq-item.hidden {
        display: none;
    }

    .faq-question {
        cursor: pointer;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-icon {
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .faq-item.active .faq-icon {
        transform: rotate(45deg);
    }

    .faq-answer {
        display: none;
        padding-top: 12px;
    }

    .faq-item.active .faq-answer {
        display: block;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.faq-category-btn');
    const faqItems = document.querySelectorAll('.faq-item');
    
    // Category filtering
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter FAQs
            faqItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });

    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.closest('.faq-item');
            faqItem.classList.toggle('active');
        });
    });
});
</script>
