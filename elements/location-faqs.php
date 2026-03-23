<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Get the current term
$term = get_queried_object();
?>

<section class="location-faqs">
    <div class="container">
        <div class="col-12">
            <?php if ( have_rows('frequently_asked_questions', $term) ) : ?>
                <div class="faq-section">
                    <h2>Frequently Asked Questions</h2>
                    <?php 
                    while( have_rows('frequently_asked_questions', $term) ): the_row();
                        $question = get_sub_field('question');
                        $answer = get_sub_field('answer');
                    ?>
                        <div class="faq-item">
                            <h3 class="faq-question">
                                <?php echo esc_html( $question ); ?>
                                <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </h3>
                            <div class="faq-answer">
                                <?php echo wp_kses_post( wpautop( $answer ) ); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>

    .faq-section h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .location-faqs .faq-section {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .location-faqs .faq-item {
        margin-bottom: 24px;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 24px;
    }

    .location-faqs .faq-item:last-child {
        border-bottom: none;
    }

    .location-faqs .faq-question {
        cursor: pointer;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--body-text);
        font-size: 20px;
        font-weight: 600;
    }

    .location-faqs .faq-icon {
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .location-faqs .faq-item.active .faq-icon {
        transform: rotate(45deg);
    }

    .location-faqs .faq-answer {
        display: none;
        padding-top: 12px;
        color: var(--body-text);
    }

    .location-faqs .faq-item.active .faq-answer {
        display: block;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.location-faqs .faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.closest('.faq-item');
            faqItem.classList.toggle('active');
        });
    });
});
</script> 