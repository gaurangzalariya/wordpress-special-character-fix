<?php

// DEMO HOW I USED IT FOR MY NEED
add_action('init', function () {
	global $wpdb;
	
	$posts = $wpdb->get_results("
		SELECT ID, post_content 
		FROM {$wpdb->posts} 
		WHERE post_content REGEXP 'Ã|â|Â'
	");
	
	if ($posts) {
		$replacements = [
			'â€œ' => '“',
			'â€' => '”',
			'â€”' => '—',
			'â€™' => '’',
			'â„¢' => '™',
		];
		
		foreach ($posts as $post) {
			$fixed_content = strtr($post->post_content, $replacements);
			
			if ($fixed_content !== $post->post_content) {
				wp_update_post([
					'ID' => $post->ID,
					'post_content' => $fixed_content,
				]);
			}
		}
	}
});

?>