<div class="modal" id="viewFileModal<?php echo $file_id; ?>" tabindex="-1">
  <div class="modal-dialog modal-xl ">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-fw fa-image"></i> <?php echo $file_name; ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>     
      
      <center>
        <img class="img-fluid" src="<?php echo "uploads/clients/$session_company_id/$client_id/$file_reference_name"; ?>">
      </center>
      
    </div>
  </div>
</div>