<div class="modal" tabindex="-1" role="dialog" id="cancelModal" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal();"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title text-success"><i>DTS Version 5.1.0</i></h2>
            </div> --}}
            <div class="modal-body">
                <div class="alert text-success">
                    <h2><i class="fa fa-info">&nbsp;&nbsp;New Features in DTS Version 5.1.0:</i></h2><br>
                    <ul>
                        <li>Real-time Notifications: The assigned point person will now receive instant notifications upon accepting and releasing documents.</li>
                    </ul>
                    {{-- <p>This to ensure that every transaction has an allotted budget for payments. Transactions or documents that are forwarded to Budget Section for obligation after the deadline will no longer be accepted.</p> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal();" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $("#cancelModal").modal('show');
    function closeModal() {
        $("#cancelModal").hide();
    }
</script>