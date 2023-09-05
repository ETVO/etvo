<footer>
    <div class="container">
        <div class="footer-content">
            <a class="brand" href="<?= $logo_link; ?>">
                <img src="/assets/img/etvo-desc.svg" alt="etvo" class="d-none d-sm-block">
                <img src="/assets/img/etvo-desc-center.svg" alt="etvo" class="d-block d-sm-none">
            </a>
            <div class="copy">
                <div class="social">
                    <?php foreach ($socials as $social) : ?>
                        <a href="<?= $social['link']; ?>" class="bi-<?= $social['icon']; ?>" title="<?= $social['title']; ?>" target="_blank"></a>
                    <?php endforeach; ?>
                </div>
                <span><?= date('Y'); ?> &copy; ETVO.</span>
                <span class="stmt">Developed from <a href="/about-etvo-manage">scratch</a>.</span>
            </div>
            <div class="contact-action">
                <a href="/#contact" class="btn btn-primary">
                    <span class="bi-person-fill"></span>
                    talk to etvo
                </a>
            </div>
        </div>
    </div>
</footer>

</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="/assets/js/main.js"></script>

</html>