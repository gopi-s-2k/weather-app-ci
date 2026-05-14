<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-body text-center p-5">
                <h4 class="text-danger mb-3">Error!</h4>
                <p id="err-model-msg" class="mb-4">An Error occured. </p>
                <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    function showErrorAlert(message){
        $("#errorModal #err-model-msg").text(message);
        $('#errorModal').modal('show');
    }
</script>
<?= $this->endSection() ?>
