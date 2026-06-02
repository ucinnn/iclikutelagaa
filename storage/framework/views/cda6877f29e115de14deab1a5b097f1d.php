<footer>
    <div class="bg-gray-800 text-gray-300 pt-10 pb-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">

                
                <div class="w-full lg:w-1/3 px-4 pb-5">
                    <?php
                        try {
                            $brandName = filament()->getBrandName();
                        } catch (\Throwable $e) {
                            $brandName = null;
                        }

                        $brandName = $brandName ?? config('app.name') ?? env('APP_NAME', 'One LTG');

                        try {
                            $brandLogo = filament()->getBrandLogo();
                        } catch (\Throwable $e) {
                            $brandLogo = null;
                        }

                        if (empty($brandLogo)) {
                            $brandLogo = config('app.logo')
                                ? asset(config('app.logo'))
                                : (env('APP_LOGO')
                                    ? asset(env('APP_LOGO'))
                                    : asset('images/logo.png'));
                        }

                        try {
                            $brandLogoHeight = filament()->getBrandLogoHeight() ?? '40px';
                        } catch (\Throwable $e) {
                            $brandLogoHeight = '40px';
                        }
                    ?>

                    <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 flex-shrink-0 min-w-0 mb-4">
                        <img
                            src="<?php echo e($brandLogo); ?>"
                            alt="<?php echo e($brandName); ?>"
                            style="height: <?php echo e($brandLogoHeight); ?>; width: auto;"
                            class="object-contain"
                            onerror="this.src='<?php echo e(asset('images/logo.png')); ?>';"
                        >
                        <span class="text-2xl font-bold text-white"><?php echo e($brandName); ?></span>
                    </a>

                    <?php
                        use App\Models\About;
                        $about = About::first();
                    ?>

                    <p class="mb-6 text-sm text-gray-400 leading-relaxed">
                        <?php echo $about ? $about->description : 'Konten belum tersedia.'; ?>

                    </p>

                    <?php
                        $socialLinks = \App\Models\SocialLink::all();
                    ?>

                    <ul class="flex items-center space-x-3 text-gray-400 border-t border-gray-700 pt-3">
                        <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e($link->url); ?>" target="_blank" class="hover:text-blue-500 transition">
                                    <?php switch(strtolower($link->platform)):
                                        
                                        case ('facebook'): ?> <i class="fab fa-facebook-f"></i> <?php break; ?>
                                        <?php case ('twitter'): ?> <i class="fab fa-twitter"></i> <?php break; ?>
                                        <?php case ('x'): ?> <i class="fab fa-x-twitter"></i> <?php break; ?>
                                        <?php case ('instagram'): ?> <i class="fab fa-instagram"></i> <?php break; ?>
                                        <?php case ('tiktok'): ?> <i class="fab fa-tiktok"></i> <?php break; ?>
                                        <?php case ('youtube'): ?> <i class="fab fa-youtube"></i> <?php break; ?>
                                        <?php case ('threads'): ?> <i class="fab fa-threads"></i> <?php break; ?>
                                        <?php case ('snapchat'): ?> <i class="fab fa-snapchat-ghost"></i> <?php break; ?>
                                        <?php case ('pinterest'): ?> <i class="fab fa-pinterest-p"></i> <?php break; ?>
                                        <?php case ('reddit'): ?> <i class="fab fa-reddit-alien"></i> <?php break; ?>

                                        
                                        <?php case ('whatsapp'): ?> <i class="fab fa-whatsapp"></i> <?php break; ?>
                                        <?php case ('telegram'): ?> <i class="fab fa-telegram-plane"></i> <?php break; ?>
                                        <?php case ('discord'): ?> <i class="fab fa-discord"></i> <?php break; ?>
                                        <?php case ('line'): ?> <i class="fab fa-line"></i> <?php break; ?>
                                        <?php case ('messenger'): ?> <i class="fab fa-facebook-messenger"></i> <?php break; ?>

                                        
                                        <?php case ('linkedin'): ?> <i class="fab fa-linkedin-in"></i> <?php break; ?>
                                        <?php case ('github'): ?> <i class="fab fa-github"></i> <?php break; ?>
                                        <?php case ('gitlab'): ?> <i class="fab fa-gitlab"></i> <?php break; ?>
                                        <?php case ('dribbble'): ?> <i class="fab fa-dribbble"></i> <?php break; ?>
                                        <?php case ('behance'): ?> <i class="fab fa-behance"></i> <?php break; ?>
                                        <?php case ('medium'): ?> <i class="fab fa-medium-m"></i> <?php break; ?>
                                        <?php case ('codepen'): ?> <i class="fab fa-codepen"></i> <?php break; ?>

                                        
                                        <?php case ('vimeo'): ?> <i class="fab fa-vimeo-v"></i> <?php break; ?>
                                        <?php case ('twitch'): ?> <i class="fab fa-twitch"></i> <?php break; ?>
                                        <?php case ('spotify'): ?> <i class="fab fa-spotify"></i> <?php break; ?>
                                        <?php case ('soundcloud'): ?> <i class="fab fa-soundcloud"></i> <?php break; ?>

                                        
                                        <?php case ('shopify'): ?> <i class="fab fa-shopify"></i> <?php break; ?>
                                        <?php case ('amazon'): ?> <i class="fab fa-amazon"></i> <?php break; ?>
                                        <?php case ('ebay'): ?> <i class="fab fa-ebay"></i> <?php break; ?>

                                        
                                        <?php case ('wechat'): ?> <i class="fab fa-weixin"></i> <?php break; ?>
                                        <?php case ('tumblr'): ?> <i class="fab fa-tumblr"></i> <?php break; ?>
                                        <?php case ('quora'): ?> <i class="fab fa-quora"></i> <?php break; ?>
                                        <?php case ('rss'): ?> <i class="fas fa-rss"></i> <?php break; ?>

                                        
                                        <?php default: ?> <i class="fas fa-globe"></i>
                                    <?php endswitch; ?>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

            </div>
        </div>

        
    </div>

    
    <div class="bg-gray-900">
        <div class="container mx-auto h-16 flex items-center justify-center py-4">
            <span class="text-sm text-gray-500 text-center">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script>
                <a href="<?php echo e(config('app.url')); ?>" target="_blank" class="hover:underline">
                    <?php echo e($brandName); ?>

                </a> PT. Liku Telaga All rights reserved. <br>
                Made by
                <a href="https://github.com/FarhanFuady090" target="_blank" class="text-gray-300 hover:text-white inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                </a>
                <a href="https://github.com/FarhanFuady090" target="_blank" class="text-gray-300 hover:text-white">Muhammad Farhan Fuady</a>
            </span>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\TA\Information Center FInal (1)\resources\views/components/layouts/footeruser.blade.php ENDPATH**/ ?>