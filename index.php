    <?php include_once 'includes/header.php'; ?>
    <?php 
    session_start();
    if(!isset($_SESSION['login']))
    {
        header( 'Location:login.php');
        exit(); // Add exit after redirect
    }

    function generateToken($url) {
        $secretKey = 'Ydfsdgfdg!£@£!@£@!£@£'; // Must match proxy.php's key.
        $expiration = time() + 86400; // 24 hours expiration.
        $alias = bin2hex(random_bytes(8));
        $_SESSION['url_map'][$alias] = $url;
        $data = $alias . $expiration;
        $signature = hash_hmac('sha256', $data, $secretKey);
        return [$alias, urlencode(base64_encode("$expiration:$signature"))];
    }

    // Enable error reporting for debugging.
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ?>

    <body>
        <div class="container">
            <h1>The Best Couch Potato List!</h1>
            <p><span style="font-family: Arial, sans-serif"><strong><u>Please click and download
                            <a href="https://brave.com/" class="gold-text" style="text-decoration: underline;">
                                <img src="images/S2UB31723335663.png" alt="BRAVE Browser"
                                    style="height: 24px; vertical-align: middle;">
                            </a>
                            OR use this AdBlocker
                            <a href="https://ublockorigin.com/" class="gold-text" style="text-decoration: underline;">
                                <img src="images/97UKj1723335831.png" alt="Ad Blocker"
                                    style="height: 24px; vertical-align: middle;">
                            </a>
                            BEFORE clicking on one of the links below (Otherwise you will see
                            ads/popups!)</u></strong></span></p>


            <div id="qrCode" style="display:none; text-align: center; margin-top: 5px;">
                <img src="images/0w6FH1724939630.jpg" alt="QR Code" style="width: 180px;">
            </div>

            <!-- Movies & Shows Section -->
            <div class="section">
                <h2 class="section-header">Movies & Shows</h2>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://novafork.com/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/Vksv2027WvY.png" alt="Novafork Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://catflix.su/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/catflix-dark.svg" alt="CatFlix Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://www.popcornmovies.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo-1723617006.png" alt="PopcornMovies Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://www.rgshows.me/?p=1'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo.png" alt="RG Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://fmovies-hd.to/home/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/800px-FMovies_Logo.png" alt="Fmovies Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://flixwave.watch/home/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_1.png" alt="Flixwave Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://bflix.sh/home/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_9.png" alt="Bflix Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://faragski.com/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/embed-logo.png" alt="Faragski Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://www.cinebook.xyz/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="https://www.cinebook.xyz/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo-white.00d35c63.png&w=256&q=75"
                            alt="cinebook Logo" class="icon">
                    </a>
                </div>
            </div>

            <!-- Anime Section -->
            <div class="section">
                <h2 class="section-header">Anime</h2>
                <div class="icon-link">

                    <?php list($alias, $token) = generateToken('https://hianime.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_2.png" alt="HiAnime Logo" class="icon">
                    </a>
                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://aniwatchtv.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_3.png" alt="Aniwatch Logo" class="icon">
                    </a>
                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://kaido.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_4.png" alt="Kaido  Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://anicrush.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_10.png" alt="Anicrush Logo" class="icon">
                    </a>

                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://gogoanime3.co/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_12.png" alt="GoGoAnime Logo" class="icon">
                    </a>

                </div>
            </div>

            <!-- Manga Section -->
            <div class="section">
                <h2 class="section-header">Manga</h2>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://mangadex.org/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/jxl7y1724993726.png" alt="MangaDex Logo" class="icon">
                    </a>

                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://mangareader.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_7.png" alt="MangaReader Logo" class="icon">
                    </a>

                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://mangafire.to/home'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_5.png" alt="mangafires Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://allmanga.to/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/3p7jx1724994756.png" alt="AllManga Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://mangakakalot.com/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_6.png" alt="MangaKakalot Logo" class="icon">
                    </a>
                    <!-- <a href="https://mangakakalot.com/" target="_blank">
                        <img src="images/logo_6.png" alt="MangaKakalot Logo" class="icon">
                    </a> -->
                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://asuracomic.net/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo.webp" alt="Asuracomic Logo" class="icon">
                    </a>

                </div>
            </div>

            <!-- Live TV Section -->
            <div class="section">
                <h2 class="section-header">Live-Tv</h2>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://thetvapp.to'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="https://thetvapp.to/img/TheTVApp.svg" alt="TheTvApp Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://tv247.us/all-channels/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo-1.png" alt=" TV247 Logo" class="icon">
                    </a>

                </div>
                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://usatvgo.live/'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/oK56s1723286576.png" alt="USATVGO Logo" class="icon">
                    </a>
                    <!-- <a href="https://usatvgo.live/" target="_blank">
                        <img src="images/oK56s1723286576.png" alt="USATVGO Logo" class="icon">
                    </a> -->
                </div>

                <div class="icon-link">
                    <?php list($alias, $token) = generateToken('https://the.streameast.app/v86'); ?>
                    <a href="proxy.php?alias=<?= $alias ?>&token=<?= $token ?>" target="_blank">
                        <img src="images/logo_8.png" alt="StreamEast" class="icon">
                    </a>

                </div>

            </div>

            <!-- PAID Movies & Shows Section -->
            <div class="section">
                <h2 class="section-header">Paid-Movies & Shows</h2>
                <div class="icon-link">
                    <a href="https://www.disneyplus.com/identity/login/" target="_blank">
                        <img src="images/dp2024_mainlogo_1c11458c.png" alt="Disney+ Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://auth.hulu.com/web/login/" target="_blank">
                        <img src="images/Hulu-Logo.wine.png" alt="HULU Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://www.netflix.com/" target="_blank">
                        <img src="images/Logonetflix.png" alt="Netflix Logo" class="icon">
                    </a>
                </div>

                <div class="icon-link">
                    <a href="https://www.max.com/" target="_blank">
                        <img src="images/max-h-w-l.svg" alt="MAX Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://tv.apple.com/" target="_blank">
                        <img src="images/e88b7ed1cb586cf4a763bb4eef4e07ac.png" alt="AppleTv+ Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://www.amazon.com/gp/video/collection/IncludedwithPrime" target="_blank">
                        <img src="images/Amazon_Prime_Video_logo.svg" alt="AmazonPrime Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://www.paramountplus.com/account/signin/" target="_blank">
                        <img src="images/pplus_logo_white.svg" alt="Paramount+ Logo" class="icon">
                    </a>
                </div>
                <div class="icon-link">
                    <a href="https://sso-v2.crunchyroll.com/login" target="_blank">
                        <img src="images/1024px-Crunchyroll.svg.png" alt="CrunchyRoll Logo" class="icon">
                    </a>
                </div>
            </div>
        </div>
        <?php include_once 'includes/footer.php'; ?>