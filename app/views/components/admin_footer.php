</main><!-- End Main Content -->
</div><!-- End Admin Container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    // Auto-Initialize SimpleDataTables for tables with class 'datatable' or specific IDs
    document.addEventListener('DOMContentLoaded', () => {
        const tables = ['#usersTable', '#pengajuanTable', '#pTable', '#jurusanTable', '#kelasTable', '#mataKuliahTable']; // List of tables to paginize

        tables.forEach(selector => {
            const el = document.querySelector(selector);
            if (el) {
                // Check if it's pTable (dynamic) - wait for render?
                // For static tables (Users, Pengajuan), init immediately.
                if (selector !== '#pTable') {
                    new simpleDatatables.DataTable(el, {
                        perPage: 10,
                        perPageSelect: [10, 20, 50],
                        columns: [{ select: -1, sortable: false }]
                    });
                }
            }
        });
    });
</script>
</body>

</html>