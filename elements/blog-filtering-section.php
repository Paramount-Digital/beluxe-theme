<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="resource-filtering">
	
	<div class="container">
		
		<form action="<?php echo esc_url('/'); ?>" method="GET" class="filtering-form col-12">

			<fieldset class="resource-radio-wrap">

				<legend>Sort By</legend>

				<span class="resource-radio-button">
					<label>
						<input type="radio" name="sortby" value="latest" <?php if((isset($_GET['sortby']) && $_GET['sortby'] == 'latest') || (!isset($_GET['sortby']))) { echo 'checked="checked"'; }; ?> >
						<span class="list-item-label">Latest</span>
					</label>
				</span>

				<span class="resource-radio-button">
					<label>
						<input type="radio" name="sortby" value="oldest" <?php if(isset($_GET['sortby']) && $_GET['sortby'] == 'oldest') { echo 'checked="checked"'; }; ?> >
						<span class="list-item-label">Oldest</span>
					</label>
				</span>
				
				<noscript>
					
					<input type="submit" value="Filter resources" />
					
				</noscript>

			</fieldset>

		</form>
		
	</div>
	
</div>