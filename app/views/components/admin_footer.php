</main><!-- End Main Content -->
</div><!-- End Admin Container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    // Auto-Initialize SimpleDataTables for tables with class 'datatable' or specific IDs
    document.addEventListener('DOMContentLoaded', () => {
        const tables = ['#usersTable', '#pengajuanTable', '#pTable', '#jurusanTable', '#kelasTable', '#mataKuliahTable', '#jadwalTable']; // List of tables to paginize

        tables.forEach(selector => {
            const el = document.querySelector(selector);
            if (el) {
                // Check if it's pTable (dynamic) - wait for render?
                // For static tables (Users, Pengajuan), init immediately.
                if (selector === '#pTable') {
                    // Skip initialization for #pTable here (it's often handled dynamically)
                    return;
                }

                if (selector === '#jurusanTable') {
                    // Special config for Jurusan Table (Disable Search)
                    new simpleDatatables.DataTable(el, {
                        searchable: false,
                        perPage: 10,
                        perPageSelect: [10, 20, 50],
                        columns: [{ select: -1, sortable: false }]
                    });
                } else {
                    // Default config for other tables (Users, Pengajuan, Jadwal, etc.)
                    new simpleDatatables.DataTable(el, {
                        // searchable: true (default)
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