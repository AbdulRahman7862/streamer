<?php include_once 'includes/header.php'; ?>
<?php 
session_start();
if(!isset($_SESSION['login']))
{
    header( 'Location:login.php');
}


function generateSignedUrl($url) {
    $token = bin2hex(random_bytes(16)); // Generate a unique token
    $_SESSION['tokens'][$token] = [
        'url' => $url,
        'expiry' => time() + 300 // 5 minutes expiration
    ];
    return "secure_page.php?token=$token"; // Securely loads the page
}
?>

<!-- Inline CSS for Filter Buttons -->
<style>
#filter-nav {
    text-align: center;
    margin-bottom: 20px;
}

.filter-btn {
    padding: 12px 20px;
    font-size: 1.2rem;
    margin: 5px;
    border: none;
    border-radius: 5px;
    background: #000000;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.filter-btn.active-filter {
    background-color: #dc2626;
    color: #fff;
}

/* Logout Button Styles */
.logout-btn {
    background-color: #dc2626;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    margin-top: 20px;
    /* adds vertical space from the heading */
    transition: background-color 0.3s;
    text-decoration: none;
    display: inline-block;
}

.logout-btn:hover {
    background-color: #b91c1c;
}

/* Add spacing after the heading */
.head {
    margin-bottom: 1rem;
    /* Adjust as needed */
}
</style>

<script>
        // Handle links within the PWA
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listeners to all links
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Don't handle internal links (like logout)
                    if (this.getAttribute('href').startsWith('/') || this.getAttribute('href').startsWith('#')) {
                        return;
                    }
                    
                    // Don't handle links that should open in new tab
                    if (this.getAttribute('target') === '_blank') {
                        return;
                    }

                    e.preventDefault();
                    const url = this.getAttribute('href');
                    
                    // Create an iframe to load the content
                    const iframe = document.createElement('iframe');
                    iframe.style.width = '100%';
                    iframe.style.height = '100vh';
                    iframe.style.border = 'none';
                    iframe.src = url;

                    // Create a container for the iframe
                    const container = document.createElement('div');
                    container.style.position = 'fixed';
                    container.style.top = '0';
                    container.style.left = '0';
                    container.style.width = '100%';
                    container.style.height = '100%';
                    container.style.backgroundColor = '#fff';
                    container.style.zIndex = '1000';

                    // Add a close button
                    const closeButton = document.createElement('button');
                    closeButton.innerHTML = '×';
                    closeButton.style.position = 'fixed';
                    closeButton.style.top = '10px';
                    closeButton.style.right = '10px';
                    closeButton.style.padding = '10px';
                    closeButton.style.fontSize = '24px';
                    closeButton.style.backgroundColor = '#dc2626';
                    closeButton.style.color = '#fff';
                    closeButton.style.border = 'none';
                    closeButton.style.borderRadius = '5px';
                    closeButton.style.cursor = 'pointer';
                    closeButton.onclick = () => container.remove();

                    // Add elements to container
                    container.appendChild(closeButton);
                    container.appendChild(iframe);
                    document.body.appendChild(container);
                });
            });
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('Service Worker registered:', registration);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }
</script>

<body>
    <div class="container">

        <h1 class="head">The Best Couch Potato List!</h1>
        <a href="logout.php" class="logout-btn" id="lout">Logout</a>

        <p class="head">
            <span style="font-family: Arial, sans-serif">
                <strong><u>
                        Please click and download
                        <a href="https://brave.com/" class="gold-text" style="text-decoration: underline;"
                            target="_blank">
                            <img src="images/S2UB31723335663.png" alt="BRAVE Browser"
                                style="height: 24px; vertical-align: middle;">
                        </a>
                        OR use this AdBlocker
                        <a href="https://ublockorigin.com/" class="gold-text" style="text-decoration: underline;"
                            target="_blank">
                            <img src="images/97UKj1723335831.png" alt="Ad Blocker"
                                style="height: 24px; vertical-align: middle;">
                        </a>
                        BEFORE clicking on one of the links below (Otherwise you will see ads/popups!)
                    </u></strong>
            </span>
        </p>


        <!-- Filter Navigation -->
        <div id="filter-nav">
            <button class="filter-btn active-filter" data-filter="all">All</button>
            <button class="filter-btn" data-filter="movies">Movies &amp; Shows</button>
            <button class="filter-btn" data-filter="anime">Anime</button>
            <button class="filter-btn" data-filter="manga">Manga</button>
            <button class="filter-btn" data-filter="livetv">Live-Tv</button>
            <button class="filter-btn" data-filter="paid">Paid-Movies &amp; Shows</button>
        </div>

        <!-- Movies & Shows Section -->
        <div class="section filter-item" data-category="movies">
            <h2 class="section-header">Movies &amp; Shows</h2>
            <div class="icon-link">
            <a href="#" onclick="openInElectron(`https://www.vidbinge.com/`)">
            <img src="images/FtAgO1725000848.png" alt="VidBinge Logo" class="icon">
        </a>
            </div>

            <div class="icon-link">
                <a href="#" onclick="openInElectron(`https://catflix.su/home`)"> 
                    <img src="images/catflix-dark.svg" alt="CatFlix Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://cinemadeck.com/" target="_blank">
                    <img src="images/hz2661MTQ.png" alt="CinemaDeck Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://www.popcornmovies.to/home" target="_blank">
                    <img src="images/logo-1723617006.png" alt="PopcornMovies Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://nepu.to/" target="_blank">
                    <img src="images/lTi8711Gm.png" alt="Nepu Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://www.rgshows.me/?p=1" target="_blank">
                    <img src="images/logo.png" alt="RG Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://broflix.cc/" target="_blank">
                    <img src="images/aQS5589hA.Png" alt="BroFlix Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://freek.to/" target="_blank">
                    <img src="images/Hej3468Ng.png" alt="Freek Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://www.cineby.ru/" target="_blank">
                    <img src="images/yM9960MRM.png" alt="Cineby Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://rivestream.live/" target="_blank">
                    <img src="images/HdLW81723624024.png" alt="RiveStream Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://fmovies-hd.to/home/" target="_blank">
                    <img src="images/800px-FMovies_Logo.png" alt="Fmovies Logo" class="icon">
                </a>
            </div>
            <!-- <div class="icon-link">
            <a href="https://flixwave.watch/home/" target="_blank">
                <img src="images/logo_1.png" alt="Flixwave Logo" class="icon">
            </a>
        </div> -->
            <div class="icon-link">
                <a href="https://bflix.sh/home/" target="_blank">
                    <img src="images/logo_9.png" alt="Bflix Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://faragski.com/" target="_blank">
                    <img src="images/embed-logo.png" alt="Faragski Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://watch.autoembed.cc/" target="_blank">
                    <img src="images/KIDcO1724709727.png" alt="autoembed Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://www.cinebook.xyz/" target="_blank">
                    <img src="https://www.cinebook.xyz/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo-white.00d35c63.png&w=256&q=75"
                        alt="cinebook Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://hydrahd.com/" target="_blank">
                    <img src="images/UnF971724715842.png" alt="Hydra Logo" class="icon">
                </a>
            </div>
        </div>

        <!-- Anime Section -->
        <div class="section filter-item" data-category="anime">
            <h2 class="section-header">Anime</h2>
            <div class="icon-link">
                <a href="https://hianime.to/home" target="_blank">
                    <img src="images/logo_2.png" alt="HiAnime Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://yukiwatch.su/home" target="_blank">
                    <img src="images/5SQWm1728817939.png" alt="Yuki Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://aniwatchtv.to/home" target="_blank">
                    <img src="images/logo_3.png" alt="Aniwatch Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://animetsu.cc/" target="_blank">
                    <img src="images/Ku7B41723286338.png" alt="Animetsu Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://kaido.to/home" target="_blank">
                    <img src="images/logo_4.png" alt="Kaido  Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://anicrush.to/home" target="_blank">
                    <img src="images/logo_10.png" alt="Anicrush Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://rivekun.rivestream.live/" target="_blank">
                    <img src="images/vXOCc1724998473.png" alt="RiveKun Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://kickassanime.am/anime" target="_blank">
                    <img src="images/logo_11.png" alt="Zorox Logo" class="icon">
                </a>
            </div>
            <!-- <div class="icon-link">
            <a href="https://gogoanime3.co/" target="_blank">
                <img src="images/logo_12.png" alt="GoGoAnime Logo" class="icon">
            </a>
        </div> -->
        </div>

        <!-- Manga Section -->
        <div class="section filter-item" data-category="manga">
            <h2 class="section-header">Manga</h2>

            <div class="icon-link">
                <a href="https://comick.io/home2" target="_blank">
                    <img src="images/q0sjq1724994374.png" alt="Comick Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://mangareader.to/home" target="_blank">
                    <img src="images/logo_7.png" alt="MangaReader Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://mangago.me/" target="_blank">
                    <img src="images/logo-new-g.png" alt="mangago Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://mangafire.to/home" target="_blank">
                    <img src="images/logo_5.png" alt="mangafires Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://allmanga.to/manga?cty=ALL" target="_blank">
                    <img src="images/3p7jx1724994756.png" alt="AllManga Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://mangakakalot.com/" target="_blank">
                    <img src="images/logo_6.png" alt="MangaKakalot Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://asuracomic.net/" target="_blank">
                    <img src="images/logo.webp" alt="Asuracomic Logo" class="icon">
                </a>
            </div>
        </div>

        <!-- Live TV Section -->
        <div class="section filter-item" data-category="livetv">
            <h2 class="section-header">Live-Tv</h2>
            <div class="icon-link">
                <a href="https://thetvapp.to" target="_blank">
                    <img src="https://thetvapp.to/img/TheTVApp.svg" alt="TheTvApp Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://tv247.us/all-channels/" target="_blank">
                    <img src="images/logo-1.png" alt=" TV247 Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://usatvgo.live/" target="_blank">
                    <img src="images/oK56s1723286576.png" alt="USATVGO Logo" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://en12.sportplus.live/" target="_blank">
                    <img src="images/logo.svg" alt="Sport+" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://the.streameast.app/v86" target="_blank">
                    <img src="images/logo_8.png" alt="StreamEast" class="icon">
                </a>
            </div>
            <div class="icon-link">
                <a href="https://v2.sportsurge.net/" target="_blank">
                    <img src="images/j4ZZN1724656768.png" alt="sportsurge Logo" class="icon">
                </a>
            </div>
        </div>

        <!-- Paid Movies & Shows Section -->
        <div class="section filter-item" data-category="paid">
            <h2 class="section-header">Paid-Movies &amp; Shows</h2>
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

        <div id="secure-container"></div>
    </div>

    <script>
    // Filter functionality for the sections
    const filterButtons = document.querySelectorAll('.filter-btn');
    const filterItems = document.querySelectorAll('.filter-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filterValue = button.getAttribute('data-filter');

            // Remove active class from all buttons and add to the clicked one
            filterButtons.forEach(btn => btn.classList.remove('active-filter'));
            button.classList.add('active-filter');

            // Loop through each section and show/hide based on the selected filter
            filterItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') ===
                    filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });


    function loadSecurePage(event, secureUrl) {
        event.preventDefault(); // Prevent normal redirection

        fetch(secureUrl)
            .then(response => response.json())
            .then(data => {
                if (data.secureUrl) {
                    let secureContainer = document.getElementById("secure-container");
                    secureContainer.innerHTML = `<iframe src="${data.secureUrl}" style="width: 100%; height: 100vh; border: none;"></iframe>`;
                } else {
                    alert("Error loading the page. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Failed to load the page.");
            });
    }
    </script>
    <?php include_once 'includes/footer.php'; ?>
</body>

</html>