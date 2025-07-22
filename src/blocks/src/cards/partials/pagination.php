<?php
$base_url = get_pagenum_link(1); 
//If there's only one page, don't continue
if ( $total_pages > 1 ) :
?>
<nav class="dgwltd-pagination govuk-pagination" aria-label="Pagination">
<ul class="govuk-pagination__list">
	<?php
 // If current page is not the first one
 if ($paged !== 1 && $total_pages > 1): ?>
	<li class="govuk-pagination__item govuk-pagination__item--previous">
		<a href="<?php echo $prev_url; ?>" class="govuk-pagination__link" rel="prev">
			<svg class="govuk-pagination__icon govuk-pagination__icon--prev" xmlns="http://www.w3.org/2000/svg" height="13" width="15" aria-hidden="true" focusable="false" viewBox="0 0 15 13">
				<path d="m6.5938-0.0078125-6.7266 6.7266 6.7441 6.4062 1.377-1.449-4.1856-3.9768h12.896v-2h-12.984l4.2931-4.293-1.414-1.414z"></path>
			</svg>
			<span class="govuk-pagination__link-title">
				Previous<span class="govuk-visually-hidden"> page</span>
			</span>
		</a>
	</li>
	<?php endif;
 if ($total_pages > 5):

   $start_page = max(2, $paged - 2);
   $end_page = min($total_pages - 1, $paged + 2);
   ?>
		<li class="govuk-pagination__item">
			<a href="<?php echo get_pagenum_link(
     '1'
   ); ?>" class="govuk-pagination__link">1</a>
		</li>
		<?php
  if ($paged > 3) {
    echo '<span>...</span>';
  }
  for ($i = $start_page; $i <= $end_page; $i++) { ?>
			<li class="govuk-pagination__item<?php echo $paged === $i
     ? ' govuk-pagination__item--active'
     : ''; ?>">
				<a href="<?php echo get_pagenum_link($i); ?>" class="govuk-pagination__link">
					<?php echo $i; ?>
				</a>
			</li>
			<?php }
  if ($paged < $total_pages - 2) {
    echo '<span class="dgwltd-pagination__seperator">...</span>';
  }
  ?>
		<li class="govuk-pagination__item">
			<a href="<?php echo get_pagenum_link(
     $total_pages
   ); ?>" class="govuk-pagination__link">
				<?php echo $total_pages; ?>
			</a>
		</li>
		<?php
 else:
   for ($i = 1; $i <= $total_pages; $i++): ?>
		<li class="govuk-pagination__item<?php echo $paged == $i
    ? ' govuk-pagination__item--active'
    : ''; ?>">
			<a href="<?php echo get_pagenum_link($i); ?>" class="govuk-pagination__link">
				<?php echo $i; ?>
			</a>
		</li>
		<?php endfor;
 endif;
 // If current page is not the same as the total number of pages
 if ($paged != $total_pages): ?>
	<li class="govuk-pagination__item govuk-pagination__item--next">
		<a href="<?php echo $next_url; ?>" class="govuk-pagination__link" rel="next">
			<span class="govuk-pagination__link-title">
				Next<span class="govuk-visually-hidden"> page</span>
			</span>
			<svg class="govuk-pagination__icon govuk-pagination__icon--next" xmlns="http://www.w3.org/2000/svg" height="13" width="15" aria-hidden="true" focusable="false" viewBox="0 0 15 13">
				<path d="m8.107-0.0078125-1.4136 1.414 4.2926 4.293h-12.986v2h12.896l-4.1855 3.9766 1.377 1.4492 6.7441-6.4062-6.7246-6.7266z"></path>
			</svg>
		</a>
	</li>
	<?php endif;
 ?>
</ul>
</nav>
<?php endif; ?>