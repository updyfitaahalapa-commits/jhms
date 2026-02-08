    </div> <!-- End Content -->
</div> <!-- End Wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom JS -->
<script src="../assets/js/script.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTables for any table with class 'table' that is not a simple layout table
        // Or specific IDs. Let's target specific IDs or generic approach.
        // For now, let's target #jobsTable and generic .table-datatable if I use it.
        $('#jobsTable').DataTable();
        $('#applicantsTable').DataTable();
    });
</script>
</body>
</html>
