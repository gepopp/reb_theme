<?php
/**
 * @var $speaker array
 */
extract($args);
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mt-10">
    <div class="relative h-auto">
        <img src="<?php echo $speaker['bild']['sizes']['article'] ?>" class="w-full h-auto border-8 border-white" alt="<?php echo $speaker['name'] ?>"/>
		<?php if ($speaker['unternehmenswebseite'] != '' && $speaker['logo']['sizes']['xs'] != ''): ?>
            <div class="absolute bottom-0 right-0 lg:w-24 lg:h-24 w-12 h-12 -mb-12 lg:-mr-12 bg-white rounded-full p-3">
                <a href="<?php echo $speaker['unternehmenswebseite'] ?>" target="_blank">
                    <img src="<?php echo $speaker['logo']['sizes']['xs'] ?>" class="w-full h-auto lg:p-2" alt="<?php echo $speaker['name'] ?>"/>
                </a>
            </div>
		<?php endif; ?>
    </div>
    <div class="mt-12 lg:mt-2">
        <div class="bg-white px-3">
            <h1 class="text-xl lg:text-3xl text-primary-100 font-bold leading-none inline uppercase"><?php echo $speaker['name'] ?></h1>
        </div>
        <div class="text-base leading-tight text-white py-5"><?php echo $speaker['kurzbeschreibung'] ?></div>
    </div>
</div>