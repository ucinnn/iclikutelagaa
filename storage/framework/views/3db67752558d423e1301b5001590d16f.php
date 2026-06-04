<?php
    use Filament\Support\Enums\MaxWidth;

?>
<footer
    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'fi-footer my-3 flex flex-wrap items-center justify-center text-sm text-gray-500 dark:text-gray-400',
        'border-t border-gray-200 dark:border-gray-700 text-center p-2' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer' || $borderTopEnabled === true,
        'fi-sidebar gap-2' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer',
        'gap-4' => $footerPosition !== 'sidebar' && $footerPosition !== 'sidebar.footer',
        'mx-auto w-full px-4 md:px-6 lg:px-8' => $footerPosition === 'footer',
        match ($maxContentWidth ??= (filament()->getMaxContentWidth() ?? MaxWidth::SevenExtraLarge)) {
            MaxWidth::ExtraSmall, 'xs' => 'max-w-xs',
            MaxWidth::Small, 'sm' => 'max-w-sm',
            MaxWidth::Medium, 'md' => 'max-w-md',
            MaxWidth::Large, 'lg' => 'max-w-lg',
            MaxWidth::ExtraLarge, 'xl' => 'max-w-xl',
            MaxWidth::TwoExtraLarge, '2xl' => 'max-w-2xl',
            MaxWidth::ThreeExtraLarge, '3xl' => 'max-w-3xl',
            MaxWidth::FourExtraLarge, '4xl' => 'max-w-4xl',
            MaxWidth::FiveExtraLarge, '5xl' => 'max-w-5xl',
            MaxWidth::SixExtraLarge, '6xl' => 'max-w-6xl',
            MaxWidth::SevenExtraLarge, '7xl' => 'max-w-7xl',
            MaxWidth::Full, 'full' => 'max-w-full',
            MaxWidth::MinContent, 'min' => 'max-w-min',
            MaxWidth::MaxContent, 'max' => 'max-w-max',
            MaxWidth::FitContent, 'fit' => 'max-w-fit',
            MaxWidth::Prose, 'prose' => 'max-w-prose',
            MaxWidth::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
            MaxWidth::ScreenMedium, 'screen-md' => 'max-w-screen-md',
            MaxWidth::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
            MaxWidth::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
            MaxWidth::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',
            default => $maxContentWidth,
        } => $footerPosition === 'footer',
    ]); ?>"
>
    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex items-center gap-2' => $isHtmlSentence]); ?>">&copy; <?php echo e(now()->format('Y')); ?> -
        <?php if($sentence): ?>
            <?php if($isHtmlSentence): ?>
                <span class="flex items-center gap-2"><?php echo $sentence; ?></span>
            <?php else: ?>
                <?php echo e($sentence); ?>

            <?php endif; ?>
        <?php else: ?>
            <?php echo e(config('filament-easy-footer.app_name')); ?>

        <?php endif; ?>
    </span>

    <?php if($githubEnabled): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('devonab.filament-easy-footer.github-version', ['showLogo' => $showLogo,'showUrl' => $showUrl]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1673600393-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?>

    <?php if($logoPath): ?>
        <span class="flex items-center gap-2">
            <?php if($logoText): ?>
                <span><?php echo e($logoText); ?></span>
            <?php endif; ?>
            <?php if($logoUrl): ?>
                <a href="<?php echo e($logoUrl); ?>" class="inline-flex" target="_blank">
                    <?php endif; ?>
                    <img
                        src="<?php echo e($logoPath); ?>"
                        alt="Logo"
                        class="w-auto object-contain"
                        style="height: <?php echo e($logoHeight); ?>px;"
                    >
                    <?php if($logoUrl): ?>
                </a>
            <?php endif; ?>
        </span>
    <?php endif; ?>

    <?php if($loadTime): ?>
        <?php if($footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer'): ?>
            <span class="w-full"><?php echo e($loadTimePrefix ?? ''); ?> <?php echo e($loadTime); ?>s</span>
        <?php else: ?>
            <span><?php echo e($loadTimePrefix ?? ''); ?> <?php echo e($loadTime); ?>s</span>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(count($links) > 0): ?>
        <ul class="gap-2 flex">
            <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e($link['url']); ?>" class="text-primary-600 dark:text-primary-400 hover:text-primary-600 dark:hover:text-primary-300" target="_blank"><?php echo e($link['title']); ?></a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</footer>
<?php /**PATH C:\laragon\www\TA\Information Center FInal (1)\resources\views/vendor/filament-easy-footer/easy-footer.blade.php ENDPATH**/ ?>