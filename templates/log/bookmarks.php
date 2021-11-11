<?php
$user = wp_get_current_user();

global $wpdb;
$bookmarks = $wpdb->get_results(sprintf('SELECT * FROM wp_user_bookmarks WHERE user_id = %d ORDER BY created_at DESC LIMIT 10', $user->ID));
$bookmarks_count = $wpdb->get_var(sprintf('SELECT count(*) FROM wp_user_bookmarks WHERE user_id = %d ORDER BY created_at', $user->ID));

$bookmarks_table = [];
foreach ($bookmarks as $d) {

    $cat = get_the_category($d->post_id);
    $cat = array_shift($cat);

    $author = 'Von ' . get_the_author_meta('display_name', get_post_field('post_author', $d->post_id)) . ' am ' . get_the_time('d.m.Y', $d->post_id);


    $bookmarks_table[] = [
        'id'        => $d->id,
        'title'     => html_entity_decode(get_the_title($d->post_id)),
        'permalink' => get_the_permalink($d->post_id),
        'cat'       => $cat->name ?? '',
        'author'    => $author,
        'time'      => \Carbon\Carbon::parse($d->created_at)->diffForHumans(),
    ];
}
?>
<script>
    var bookmarks = <?php echo str_replace("'", '"', json_encode($bookmarks_table)) ?>;
</script>
<table x-show.transition.in.opacity.duration.750ms="active == 'bookmarks'" class="w-full table-auto">
    <tbody x-data="logs('bookmarks', bookmarks, <?php echo $bookmarks_count ?>, <?php echo $user->ID ?>)">
    <tr x-show="logs.length === 0" class="h-64">
        <td colspan="6" style="height: 500px">
            <div class="flex h-full w-full items-center justify-center">
                <p><?php sprintf(__('Noch keine Inhlate vorhanden.<br><a href="%s" class="cursor-pointer underline text-primary-100">Zur Startseite</a>', 'ir21'), home_url()) ?></p>
            </div>
        </td>
    </tr>
    <template x-for="log in logs" x-key="log.id">
        <tr class="mb-3 border-b border-primary-100">
            <td class="p-3">
                <a :href="log.permalink" class="hover:underline font-semibold text-lg" x-text="log.title"></a>
                <div class="w-full flex justify-between">
                    <div class="text-gray-500 text-sm" x-text="log.cat"></div>
                    <div class="text-gray-500 text-sm" x-text="log.author"></div>
                    <div class="text-gray-500 text-sm" x-text="log.time"></div>
                    <div class="text-warning underline cursor-pointer text-sm" @click="removeBookmark(log.id)"><?php _e('löschen', 'ir21') ?></div>
                </div>
            </td>
        </tr>
    </template>
    <tr>
        <td>
            <div x-show="logs.length > all" class="flex justify-center mt-5">
                <div class="px-2 py-2 bg-primary-100 text-white cursor-pointer" @click="loadNext()"><?php _e('weitere laden', 'ir21') ?></div>
            </div>
        </td>
    </tr>
    </tbody>
</table>