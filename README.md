# 🛠️ Fix Misencoded Characters in WordPress Posts (functions.php version)

This script fixes special character issues (like `â€™`, `Ã©`, `â€”`, `â€œ`, `â„¢`) in WordPress post content that often occur after content migration — especially from platforms like **HubSpot**, **Drupal**, or from copy-pasting from Word/Google Docs.

---

## 💥 Problem

You may see broken characters like:

| Broken  | Should Be |
|---------|-----------|
| â€™     | ’         |
| â€œ     | "         |
| â€"     | —         |
| â€¦     | …         |
| Ã©     | é         |
| â„¢     | ™         |
| Â       | *(removed)* |

This is caused by encoding mismatches (typically UTF-8 content interpreted as Latin-1).

---

## ✅ How to Use

1. Open your WordPress theme's `functions.php` file.
2. Add the full script shown below.
3. Visit your website (any frontend or `/wp-admin/`) to trigger the fix.
4. Once the issue is resolved, **comment out or remove the script** to avoid it re-running.

---

## 📜 Script (Add to `functions.php`)

```php
add_action('init', function () {
    if (!get_option('fix_garbage_characters_run')) {
        global $wpdb;

        $posts = $wpdb->get_results("
            SELECT ID, post_content 
            FROM {$wpdb->posts} 
            WHERE post_content REGEXP 'Ã|â|Â'
        ");

        if ($posts) {
            $replacements = [
                'â€œ' => '“', 'â€' => '”',
                'â€˜' => '‘', 'â€™' => '’',
                'â€" => '—', 'â€¦' => '…',
                'â€¢' => '•', 'â‚¬' => '€',
                'â„¢' => '™', 'Ã©' => 'é',
                'Ã¨' => 'è', 'Ã¢' => 'â', 'Ãª' => 'ê',
                'Ã«' => 'ë', 'Ã§' => 'ç', 'Ã¹' => 'ù', 'Ã¼' => 'ü',
                'Ã ' => 'à', 'Ã´' => 'ô', 'Ã¶' => 'ö', 'Ã®' => 'î',
                'Ã¯' => 'ï', 'Ã¡' => 'á', 'Ã­' => 'í', 'Ã³' => 'ó',
                'Ãº' => 'ú', 'Ã±' => 'ñ', 'ÃŸ' => 'ß',
                'Ã¢â‚¬â„¢' => '’', 'Ã¢â‚¬Å" => '“', 'Ã¢â‚¬Â' => '”',
                'Ã¢â‚¬Â¦' => '…', 'Ã¢â‚¬Â¢' => '•', 'Ã¢â‚¬â€œ' => '–',
                'Ã¢â‚¬â€' => '—',
                'Â' => '',
            ];

            foreach ($posts as $post) {
                $fixed = strtr($post->post_content, $replacements);

                if ($fixed !== $post->post_content) {
                    wp_update_post([
                        'ID' => $post->ID,
                        'post_content' => $fixed,
                    ]);
                }
            }
        }

        update_option('fix_garbage_characters_run', true);
    }
});
```

---

## 🔁 Re-run the Fix

If you want to re-run the script (for example, after another import or migration), just add this one-liner above the main script:

```php
delete_option('fix_garbage_characters_run');
```

Then refresh your site to trigger the fix again.

## ✅ After Running

Comment out or remove the script from `functions.php` once the content is corrected.

> ⚠️ **Important**: Always take a database backup before running scripts that change post content.

---

## 🙌 Author

Made with ❤️ by [Gaurang Zalariya](https://linktr.ee/gaurangzalariya)  
MIT License – Free to use, modify, and share.

## 🔎 Related Keywords

wordpress special character fix, wordpress encoding issues, hubspot to wordpress migration, utf-8 latin1 mismatch, â€™ â€" Ã© character bug, fix broken characters wordpress