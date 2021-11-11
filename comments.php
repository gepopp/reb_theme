<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Freeshifter
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$gray = 'gray-800';
if ( get_post_format() == 'video' || is_singular('immolive')) {
	$gray = 'white';
}
$link = get_the_permalink();
$link .= '#comments';
$link = base64_encode( $link );
?>

<div id="comments" class="comments-area h-full">
	<?php
	$user  = wp_get_current_user();
	$image = get_field( 'field_5ded37c474589', 'user_' . $user->ID );
	?>
    <div x-data="addComment(<?php echo $user->ID ?>, <?php the_ID(); ?>)" x-init="init()" class="h-full flex flex-col">
        <div class="flex space-x-3 pb-5 mb-5 pr-5">
			<?php if ( is_user_logged_in() ): ?>
				<?php if ( $image ): ?>
                    <img src="<?php echo $image['sizes']['author_small'] ?>" class="rounded-full w-16 h-16 p-1 border border-<?php echo $gray ?>">
				<?php else: ?>
					<?php echo get_avatar( $user, 48, null, null, [ 'class' => 'rounded-full w-12 h-12 p-1 border border-' . $gray ] ) ?>
				<?php endif; ?>
                <div class="flex-grow flex-1 relative">
                        <textarea
                                x-model="comment"
                                type="text"
                                class="scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 resize-none rounded-l shadow-xl bg-gray-100 w-full  text-gray-800 border-b border-white block w-full py-2 px-2 leading-tight appearance-none focus:outline-none placeholder-gray-500 h-12"
                                placeholder="Schreiben Sie einen neuen Kommentar ..." @keydown.enter="validate()"></textarea>
                    <p class="text-xs text-aktuelles-100 absolute" x-text="commentError" x-show="commentError"></p>
                    <p class="text-right font-medium text-sm cursor-pointer" @click="validate()">SENDEN</p>
                </div>
			<?php else: ?>
                <div class="w-full">
                    <p class="font-semibold text-primary-100">Zum kommentieren bitte</p>
                    <div class="flex space-x-5 w-full">
                        <div class="flex-1">
                            <a href="<?php echo add_query_arg( [ 'redirect' => $link ], get_field( 'field_601bbffe28967', 'option' ) ); ?>" class="block w-full py-3 border border-primary-100 text-primary-100 font-medium text-center">einloggen</a>
                        </div>
                    </div>
                    <p class="text-<?php echo $gray ?>">Wenn Sie noch keinen Account haben können Sie sich hier
                        <a href="<?php echo add_query_arg( [ 'redirect' => $link ], get_field( 'field_601bc00528968', 'option' ) ); ?>">registrieren</a>
                    </p>
                </div>
			<?php endif; ?>
        </div>
        <hr>

        <div x-show="isLoading" x-cloak>
            <div class="w-full h-64 flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-<?php echo $gray ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>


        <div x-show="comments.length == 0 && !isLoading" x-cloak class="w-full h-full flex items-center justify-center bg-<?php echo $gray ?>">
            <p class="text-primary-100 text-xl font-semibold mx-10 text-center">Schreiben Sie den ersten Kommentar!</p>
        </div>


        <div class="relative transition-all duration-700">
            <template x-for="c in comments" x-key="comment.id">
                <div class="flex space-x-2 py-3 pr-3">
                    <div>
                        <img :src="c.author_avatar_urls[48]" class="rounded-full p-1 border border-white w-10 h-10">
                    </div>
                    <div class="flex-1">
                        <p>
                            <span class="text-xs font-medium text-<?php echo $gray ?> text-opacity-60" x-text="c.author_name"></span> -
                            <span x-text="formatDate(c.date)" class="text-opacity-60 text-xs m-0 text-<?php echo $gray ?>"></span>
                        </p>
                        <div class="text-<?php echo $gray ?> text-lg comment" x-html="c.content.rendered"></div>
                        <p class="text-<?php echo $gray ?> uppercase font-medium text-xs cursor-pointer m-0 text-opacity-60 hover:text-opacity-100 transition duration-150 ease-in-out" @click="openAnswer(c)" x-show="c.id != addAnswer">
                            <span x-text="c.child_count + ' - '" x-show="c.child_count > 0" class="text-<?php echo $gray ?>"></span>Antworten
                        </p>

                        <div x-show="addAnswer == c.id" @click.away="addAnswer = false">
                            <template x-for="child in children">
                                <div>
                                    <div class="shadow rounded-md p-4 w-full mx-auto" x-show="!child.id">
                                        <div class="animate-pulse flex space-x-4 items-center">
                                            <div class="rounded-full bg-gray-100 h-10 w-10"></div>
                                            <div class="flex-1 space-y-4 py-1">
                                                <div class="space-y-2">
                                                    <div class="h-4 bg-gray-100 rounded"></div>
                                                    <div class="h-4 bg-gray-100 rounded w-5/6"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show.transition.fade.in="child.id" class="flex space-x-4 items-center px-4 py-2">
                                        <div>
                                            <img :src="child.author_avatar_urls[48]" class="rounded-full p-1 border border-<?php echo $gray ?> w-10 h-10 flex-none">
                                        </div>
                                        <div class="flex-1">
                                            <p>
                                                <span class="text-xs font-medium text-<?php echo $gray ?>" x-text="c.author_name"></span> -
                                                <span x-text="formatDate(c.date)" class="text-xs m-0 text-<?php echo $gray ?>"></span>
                                            </p>
                                            <div class="text-<?php echo $gray ?> text-lg comment" x-html="child.content.rendered"></div>
                                        </div>
                                    </div>
                            </template>


							<?php if ( is_user_logged_in() ): ?>
                                <div class="flex items-end space-x-4 pr-5">
                                        <textarea
                                                x-model="answer"
                                                type="text"
                                                class="scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 resize-none rounded-l shadow-xl mt-3 bg-gray-100 w-full  text-gray-800 border-b border-white block w-full py-2 px-2 leading-tight appearance-none focus:outline-none placeholder-gray-500 h-12"
                                                placeholder="Antworten Sie hier ..." @keydown.enter="validate(c.id)"></textarea>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
