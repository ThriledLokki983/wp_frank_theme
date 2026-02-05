<?php if (empty($_SESSION['frank']['employee'])) : ?>
    <?php if (get_the_id() !== 13093) : ?>
        <footer class="footer" data-page-name="<?php echo strtolower(get_the_title()); ?>">
            <div class="footer__container">
                <p>&copy; <?= date('Y') ?> · Designed and developed by ·  &nbsp;</p>
                <a href="https://nimoh-ict.nl" target="_blank" class="link">Nimoh Digital Solutions</a>
            </div>
        </footer>
    <?php endif; ?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>