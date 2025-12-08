</main>
<div class="container">
    <hr>
    <footer class="py-3 my-4">
        <p class="text-center text-muted">
            &copy; <?php echo date('Y'); ?> Sistema de Orçamento -
            <?php echo isset($_SESSION['versao']) ? $_SESSION['versao'] : 'v2.0'; ?>
        </p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>