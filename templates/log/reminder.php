<?php
$user = wp_get_current_user();

global $wpdb;
$reminder = $wpdb->get_results(sprintf('SELECT * FROM wp_user_read_later WHERE user_id = %d ORDER BY created_at DESC LIMIT 10', $user->ID));
$reminder_count = $wpdb->get_var(sprintf('SELECT count(*) FROM wp_user_read_later WHERE user_id = %d ORDER BY created_at', $user->ID));

$reminder_table = [];
foreach ($reminder as $d) {

    $cat = get_the_category($d->post_id);
    $cat = array_shift($cat);

    $author = 'von ' . get_the_author_meta('display_name', get_post_field('post_author', $d->post_id)) . ' am ' . get_the_time('d.m.Y', $d->post_id);

    $reminder_table[] = [
        'id'        => $d->id,
        'title'     => html_entity_decode(get_the_title($d->post_id)),
        'permalink' => get_the_permalink($d->post_id),
        'cat'       => $cat->name ?? '',
        'author'    => $author,
        'time'      => ucfirst(\Carbon\Carbon::parse($d->remind_at)->diffForHumans()),
        'date'      => $d->remind_at,
    ];
}
?>
<script>
    var reminder = <?php echo str_replace("'", '"', json_encode($reminder_table)) ?>;
</script>

<div x-show.transition.in.opacity.duration.750ms="active == 'reminder'">
    <div x-data="logs('reminder', reminder, <?php echo $reminder_count ?>, <?php echo $user->ID ?>)">
        <table class="w-full table-auto">
            <tbody>
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
                        <a :href="log.permalink" class="hover:underline font-semibold lg:text-lg leading-none" x-text="log.title"></a>
                        <div class="w-full flex justify-between">
                            <div class="text-gray-500 text-sm hidden md:block">
                                <?php _e('Erschienen in', 'ir21') ?> <span x-text="log.cat"></span> <span x-text="log.author"></span>
                            </div>
                            <div class="text-gray-500 text-sm hidden md:block">
                                <div class="flex">
                                    <span x-text="log.time"></span>
                                    <svg class="w-4 h-4 ml-4 cursor-pointer" @click="updateReminder(log)" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </template>
            <tr>
                <td>
                    <div x-show="logs.length < all" class="flex justify-center mt-5">
                        <div class="px-2 py-2 bg-primary-100 text-white cursor-pointer" @click="loadNext()"><?php _e('weitere laden', 'ir21') ?></div>
                    </div>
                </td>
            </tr>
            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="fixed z-10 inset-0 overflow-y-auto" x-show="modalOpen">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!--
                      Background overlay, show/hide based on modal state.

                      Entering: "ease-out duration-300"
                        From: "opacity-0"
                        To: "opacity-100"
                      Leaving: "ease-in duration-200"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <!--
                      Modal panel, show/hide based on modal state.

                      Entering: "ease-out duration-300"
                        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        To: "opacity-100 translate-y-0 sm:scale-100"
                      Leaving: "ease-in duration-200"
                        From: "opacity-100 translate-y-0 sm:scale-100"
                        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    -->
                    <div class="inline-block align-bottom bg-white text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="w-12 h-12 text-primary-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline" x-text="editReminder.title"></h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <span><?php _e('Erinnerung in', 'ir21') ?> </span>
                                            <input type="number"
                                                   class="border-b inline text-primary-100 text-center font-semibold"
                                                   x-model="remindInDays"
                                                   min="1"
                                                   max="30"
                                                   step="1"
                                            >
                                            <span> <?php _e('Tagen', 'ir21') ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="setReminder()" class="w-full inline-flex justify-center border border-transparent shadow-sm px-4 py-2 bg-primary-100 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <?php _e('speichern', 'ir21') ?>
                            </button>
                            <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                <?php _e('abbrechen', 'ir21') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </tbody>
        </table>
    </div>
</div>