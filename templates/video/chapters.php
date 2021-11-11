<div x-data="{ chapters : [], currentChapter : 0 }" @cloaded.window="chapters = $event.detail.chapters" @cchange.window="currentChapter = $event.detail.chapter">
    <h3 class="text-white text-3xl font-serif text-center mb-10" x-show="chapters.length">Videokapitel</h3>
    <div x-show.transition="chapters.length > 0">
        <div x-show="chapters.length" x-key="chapter.index">
            <ol class="ml-2">
                <template x-for="chapter in chapters">
                    <li class="cursor-pointer mb-2" @click="$dispatch('goto', { chapter: chapter.index })">
                        <span x-text="chapter.title" :class="currentChapter == chapter.index ? 'font-bold' : ''"></span>
                    </li>
                </template>
            </ol>
        </div>
    </div>
</div>